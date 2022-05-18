<?php
/*
* ┏━━━┓╋╋╋╋╋┏┓┏━━━━┓
* ┃┏━┓┃╋╋╋╋┏┛┗┫┏┓┏┓┃
* ┃┗━┛┣━━┳━┻┓┏╋┫┃┃┣┻━┳━━┳┓┏┓
* ┃┏┓┏┫┏┓┃┏┓┃┃┣┫┃┃┃┃━┫┏┓┃┗┛┃
* ┃┃┃┗┫┗┛┃┗┛┃┗┫┃┃┃┃┃━┫┏┓┃┃┃┃
* ┗┛┗━┻━━┻━━┻━┻┛┗┛┗━━┻┛┗┻┻┻┛
* @author: David Ratnikov
* @website: https://github.com/ddosnikgit
*/
namespace ddosnik;

use ddosnik\utils\VKLib;
use ddosnik\utils\Utils;
use ddosnik\utils\Config;
use ddosnik\utils\Logger;
use ddosnik\utils\Terminal;
use ddosnik\console\MessagesReader;

final class VKCli {
    private Config $cfg;
    private $console = null;
    public int $peer_id = 2000000224;
    public string $shortlink;

    public function __construct()
    {
        $logger = new Logger();
        $logger->registerStatic();
        $library = new VKLib($this);
        $library->registerStatic();
        if(!file_exists(Utils::getDataFolder().'settings')) {
          @mkdir(Utils::getDataFolder().'settings');
        }
        $this->cfg = new Config(Utils::getDataFolder() . '/settings/config.yml', Config::YAML, array(
          'access_token' => 'zd3afa3466720b6a3791fffffff975aa98616f6abb574mnnnn976afd497482084cabb4c74f6h6'
        ));
        $this->startClient();
    }

    public function getLogger() : Logger {
        return Logger::getInstance();
    }

    public function getLib() : VKLib {
        return VKLib::getInstance();
    }

    public function getToken() : string {
        return $this->cfg->get('access_token');
    }

    public function updateConsole() : void {
  		if(($line = $this->console->getLine()) !== null){
        $this->getLib()->sendMessage($line, $this->peer_id);
  		}
  	}

    public function startClient() : void {
        $this->getLogger()->notice('Запускается прослушиватель VK-CLI, https://github.com/ddosnikgit/vk-cli');
        if($this->getLib()->tokenisValid()) {
            $this->getLogger()->notice('Токен подтвержден.');
        } else {
            exit($this->getLogger()->error('Проверка токена провалена, выход из программы...'));
        }
        $this->shortlink = $this->getLib()->getAccountInfo()[2];
        $this->getLogger()->notice('Получен LongPool server -> '.$this->getLib()->getPollServer()[0]);
        $this->getLogger()->info('Получена короткая ссылка -> '.$this->shortlink);
        $this->getLib()->choiseChat();
        echo Terminal::$COLOR_GREEN.$this->shortlink.Terminal::$COLOR_GRAY.'@vk-cli '.Terminal::$COLOR_DARK_GREEN.'~/menu '.Terminal::$COLOR_GRAY.'> '.Terminal::$FORMAT_RESET.'Введите ID (Например: 224): ';
        $id = trim(fgets(STDIN));
        $validate = $this->getLib()->getNameAndSurname($id);
        if($validate !== null && isset($this->getLib()->users[(int)$id])) {
          $this->peer_id = $id;
          $this->getLogger()->notice('Прослушиватель подключен к пользователю '.$validate);
        } else {
          $this->peer_id = (int) $id + 2000000000;
          $this->getLogger()->notice('Прослушиватель подключен к беседе, id: '.$id);
        }
        $this->console = new MessagesReader();
        $this->getLib()->updateMessages();
    }
}
