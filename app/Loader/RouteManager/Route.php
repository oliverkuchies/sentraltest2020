<?php


namespace app\Loader\RouteManager;


class Route
{
    private $url = "";
    private $file_name = "";
    private $call = null;
    private $params = null;
    /**
     * @description - Set a URL to a particular url and file name.
     * @param $url - url to load
     * @param $file - file to call from
     * @param $call - call is necessary to return any data
     * @param $params - params is necessary to retrieve any GET data
     */
    public function set($url, $file, $call = null, $params = null){
        $this->url = $url;
        $this->file_name = $file;
        $this->call = $call;
        $this->params = $params;
    }

    /**
     * @description - Get a URL of a particular route
     * @return string
     */
    public function getURL(){
        return $this->url;
    }


    /**
     * @description - Get file name associated with a particular route
     * @return string
     */
    public function getFileName(){
        return $this->file_name;
    }
    /**
     * @description - Get call/method associated with a particular route
     * @return string
     */
    public function getMethod(){
        return $this->call;
    }

    /**
     * @description - Get params associated with a particular route
     * @return array
     */
    public function getParams(){
        return $this->params;
    }
}