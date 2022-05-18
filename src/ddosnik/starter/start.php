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

	function prepare(){
		define('CLI_PATH', realpath(getcwd()) . DIRECTORY_SEPARATOR);
		define('START_TIME', time());
	}

	function start() {
		prepare();
		makeClassLoader();
		\ddosnik\utils\Terminal::init();
		\ddosnik\utils\Utils::init(CLI_PATH);

		new \ddosnik\VKCli();
	}
?>
