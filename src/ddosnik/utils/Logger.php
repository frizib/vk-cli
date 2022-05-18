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
namespace ddosnik\utils;

final class Logger {
    private static $instance = null;

    public function __construct() {
        static::$instance = $this;
    }

    public function registerStatic() {
  		if(static::$instance === null){
  			static::$instance = $this;
  		}
  	}

    /**
     * @return Logger|null
     */
    public static function getInstance(): ?Logger
    {
      if(static::$instance === null){
        //err
      }
      return static::$instance;
    }

    public function notice(string $text) : void {
        $this->send($text, 'NOTICE', Terminal::$COLOR_AQUA);
    }

    public function msg(string $name, string $text, string $shortlink, string $fromshort) : void {
        $this->send(Terminal::$COLOR_GREEN.$shortlink.Terminal::$COLOR_GRAY.'@vk-cli '.Terminal::$COLOR_AQUA.'~/me '.Terminal::$FORMAT_RESET.'> '.Terminal::$COLOR_DARK_BLUE.$name.Terminal::$COLOR_GRAY.' ('.$fromshort.')'.Terminal::$FORMAT_RESET.' -> '.$text, 'MSG');
    }

    public function error(string $text) : void {
        $this->send($text, 'ERROR', Terminal::$COLOR_RED);
    }

    public function info(string $text) : void {
        $this->send($text, 'INFO', Terminal::$COLOR_WHITE);
    }

    public function send(string $text, string $level, $color = '') : void {
        if($level === 'MSG') {
          Terminal::writeLine($text);
        } else {
          Terminal::writeLine(Terminal::$COLOR_AQUA.'['.date('H:i:s').'] '.$color.'['.$level.']: '.Terminal::$FORMAT_RESET.$text);
        }
    }
}
