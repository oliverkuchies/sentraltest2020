<?php

namespace app\Controllers\View;

use app\ViewManager;

class Home extends ViewManager
{

    /**
     * Load home page content by passing it to the loader.
     */
    public function loadHomePage(){
        $this->createView("index.php");
    }
}