<?php
namespace app\Loader\RouteManager;

use app\Loader\RouteManager\Route;
use app\Helpers;
class Router
{
    //Using static variables for a globally accessible Route manager :)
    public static $routes = [];
    private static $url = "";

    /**
     * @param $url
     * @param $file
     */
    public static function addRoute($url, $file, $call){
        $route = new Route();
        //Get variable between routes
        preg_match_all('/\[[^\]]*\]/', $url, $match_collection);
        if (!count($match_collection[0]) > 0)
        {
            $match_collection = null;
        }
        else{
            $match_collection = $match_collection[0];
        }
        $route->set($url, $file, $call, $match_collection);
        array_push(self::$routes, $route);
    }

    /**
     * Load router, check what the current page is
     * @saved_route - Get the current route based on the page requested
     * @selected_page - User's page GET parameter.
     */
    public function loadRouter(){
        $this->loadRoutes();
        $selected_page = Helpers::Get('page');
        //Lets assess whether a particular route exists, if so - lets pull it out :)
        $saved_route = $this->returnRoute($selected_page);
        if ($saved_route){
            if (file_exists("app/Controllers/{$saved_route->getFileName()}.php")) {
                //Parse class name and route names appropriately to ensure we can access them accordingly & dynamically
                $class_name = "app\Controllers\\".str_replace("/", "\\", $saved_route->getFileName());;
                $file_name = "app/Controllers/{$saved_route->getFileName()}.php";
                $route_raw = str_replace("/", "\\", $class_name);
                $route_obj = new $route_raw();
                call_user_func([$route_obj, $saved_route->getMethod()]);
            }
            else{
                die("File does not exist!");
            }
            //Lets call the function in the routing
            }
            else{
                die("The page you selected doesnt exist.");
            }
    }

    /**
     * Return Route Data
     */
    private function returnRoute($url){
        //Loop through each of the routes and determine which route is from the URL, we can use this route.
        foreach (self::$routes as $route){
            $route_url = $route->getURL();
            if ($route->getParams() != null) {
                foreach($route->getParams() as &$param){
                    //TODO - Support more than one parameter
                    //Trim annoying brackets from params to make them readable
                    $parsed_url = parse_url($url);
                    //Get all the URL elements of the user's URL
                    $parsed_url_elements = explode('/', $parsed_url['path']);
                    //Get the last index of the URL so we can figure out how to access the parameters
                    $last_index = count($parsed_url_elements)-1;
                    //Trim the parameter so we can set the $_GET parameter for the user to access
                    $trimmed_param = str_replace(["[", "]"], "", $param);
                    $last_parameter = $parsed_url_elements[$last_index];
                    $route_url = str_replace($param, $last_parameter, $route_url);
                    //Get parameter from current URL
                    $_GET[$trimmed_param] = $parsed_url_elements[$last_index];
                }
            }
            if ($route_url == $url){
                return $route;
            }
        }
        //No route returned, return false and throw error
        return false;
    }

    /**
     * Load routes via file to ensure page registers the function of each individual page
     */
    public function loadRoutes(){
        include_once "app/Routes.php";
    }


}