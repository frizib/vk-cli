<?php

	start();

	function makeClassLoader() {
	    require_once(CLI_PATH . "src/ddosnik/VKCli.php");
		require_once(CLI_PATH . "src/ddosnik/Thread.php");
		require_once(CLI_PATH . "src/ddosnik/ThreadManager.php");
		require_once(CLI_PATH . "src/ddosnik/Worker.php");
        require_once(CLI_PATH . "src/ddosnik/utils/Logger.php");
        require_once(CLI_PATH . "src/ddosnik/utils/Terminal.php");
        require_once(CLI_PATH . "src/ddosnik/utils/VKLib.php");
		require_once(CLI_PATH . "src/ddosnik/utils/Config.php");
		require_once(CLI_PATH . "src/ddosnik/utils/Utils.php");
		require_once(CLI_PATH . "src/ddosnik/console/MessagesReader.php");
	}

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
		if(version_compare('7.4.0', PHP_VERSION) > 0){
			critical_error('vk-cli' . ' requires PHP 7.4, but you have PHP ' . PHP_VERSION . '.');
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
		if(version_compare($pthreads_version, "3.2.0") < 0){
			critical_error("pthreads >= 3.2.0 is required, while you have $pthreads_version.");
			exit(1);
		}
		prepare();
		makeClassLoader();
		\ddosnik\utils\Terminal::init();
		\ddosnik\utils\Utils::init(CLI_PATH);

		new \ddosnik\VKCli();
	}
?>
