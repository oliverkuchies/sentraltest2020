<?php


namespace app;


trait Helpers
{
    /**
     * Get rid of those pesky whitespaces!
     * @param $s
     * @return mixed
     */
    public static function removeWhitespaces($s){
        return str_replace(" ", "", $s);
    }

    /**
     * Get $_POST parameter if its not null!
     */
    public static function Post($input){
        if (!isset($_POST[$input])){
            return "";
        }
        else{
            return filter_var($_POST[$input], FILTER_SANITIZE_STRING);
        }
    }
    /**
     * Get $_GET Input parameter if its not null!
     */
    public static function Get($input){
        if (!isset($_GET[$input])){
            return "";
        }
        else{
            return filter_var($_GET[$input], FILTER_SANITIZE_STRING);
        }
    }
}