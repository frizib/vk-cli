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

final class Utils {
   public static $dataFolder;

   public final static function init($dataFolder) {
     self::$dataFolder = rtrim($dataFolder, "/" . DIRECTORY_SEPARATOR) . "/";
   }

   public static function getDataFolder() {
     return static::$dataFolder;
   }
 }
