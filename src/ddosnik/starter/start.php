<?php

	start();

	function prepare()
	{
		define('CLI_PATH', realpath(getcwd()) . DIRECTORY_SEPARATOR);
		define('START_TIME', time());
	}

	/**
	 * @param string $message
	 * @return void
	 */
	function critical_error(string $message) : void {
		echo "[CRITICAL] $message" . PHP_EOL;
	}

	function start() {
		if(version_compare('8.1.12', PHP_VERSION) > 0){
			critical_error('vk-cli' . ' requires PHP 8.1.12, but you have PHP ' . PHP_VERSION . '.');
			critical_error("[CRITICAL] Please use the installer provided on the homepage.");
			exit(1);
		}
		if(!extension_loaded("pthreads")){
			critical_error('Unable to find the pthreads extension.');
			critical_error('Please use the installer provided on the homepage.');
			exit(1);
		}
		$pthreads_version = phpversion("pthreads");
		if(substr_count($pthreads_version, ".") < 2){
			$pthreads_version = "0.$pthreads_version";
		}
		if(version_compare($pthreads_version, "4.1.4") < 0){
			critical_error("pthreads >= 4.1.4 is required, while you have $pthreads_version.");
			exit(1);
		}
		prepare();
		if(!class_exists("ClassLoader", false)){
			if(!is_file(CLI_PATH . "src/spl/ClassLoader.php")){
				echo "[CRITICAL] Unable to find the SPL library." . PHP_EOL;
				echo "[CRITICAL] Please use provided builds or clone the repository recursively." . PHP_EOL;
				exit(1);
			}
			require_once(CLI_PATH . "src/spl/ClassLoader.php");
			require_once(CLI_PATH . "src/spl/BaseClassLoader.php");
			$autoloader = new \BaseClassLoader();
			$autoloader->addPath(CLI_PATH . "src");
			$autoloader->addPath(CLI_PATH . "src" . DIRECTORY_SEPARATOR . "spl");
			$autoloader->register(true);
			\ddosnik\utils\Terminal::init();
			\ddosnik\utils\Utils::init(CLI_PATH);

			new \ddosnik\VKCli($autoloader);
		}
	}
?>
