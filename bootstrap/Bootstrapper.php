<?php


namespace bootstrap;

use app\Loader\RouteManager\Router;
use config\Config;

class Bootstrapper
{

    /**
     *  Create config necessary for app to function
     */
    private function vars(){
        define("VIEW_FOLDER", "resources/views/");
    }
    /**
     * Load all resources necessary for app
     */
    public function load(){
      $this->autoload();
        //Load all routes and check user's URL.
        $router = new Router();
        $router->loadRouter();
    }
    public function autoload(){
        $this->vars();
        spl_autoload_register(function($class){
            $sources = [
                "$class.php",
            ];
            foreach ($sources as $source) {
                //Loading issue? Lets change the slashes
                $source = str_replace('\\', '/', $source);
                if (file_exists($source)) {
                    //Check if file exists, if so include it in our app :)
                    require_once $source;
                }
                else{
                    echo $source . " doesnt exist. /n\n";
                }
            }
        });
        Config::load();
    }
}