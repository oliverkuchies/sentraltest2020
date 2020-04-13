<?php


namespace config;


class Config
{
    private static $files = ['Database.php'];
    private static $config = [];
    public static function load(){
        /*Load all config files in database*/
        foreach(self::$files as $file){
            self::add(include("config/$file"));
        }
    }

    private static function add($data){
        /*Add to existing configuration*/
        self::$config = array_merge(self::$config, $data);
    }

    public static function get($item){
        return Config::$config[$item];
    }

}