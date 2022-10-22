<?php

declare(strict_types=1);

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

final class Utils {

  public static $dataFolder;

  public final static function init($dataFolder) : void{
    self::$dataFolder = rtrim($dataFolder, "/" . DIRECTORY_SEPARATOR) . "/";
  }

  public final static function sendRequest(string $method, array $params, string $server = 'https://api.vk.com/method/') : array{
    $thread = new SendRequestThread($method, $params, $server);
    $thread->start(); $thread->join();
    $data = $thread->data;
    $thread->quit();
    return json_decode($data, true);
  }

  public static function getDataFolder() : string{
    return static::$dataFolder;
  }
}