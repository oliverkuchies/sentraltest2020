<?php
namespace app;

class ViewManager{


    /**
     * @param $page
     * Create view in a systematic way
     */
    public function createView($page){
        $this->loadHeader();
        $this->loadBody($page);
        $this->loadFooter();
    }
    /**
     * Allow header to be generated from a given file
     */
    public function loadHeader(){
        require(VIEW_FOLDER."templates/header.php");
    }
    /**
     * Allow footer to be generated from a given file
     */
    public function loadFooter(){
        require(VIEW_FOLDER."templates/footer.php");
    }

    /**
     * @param $data
     * Write data to page in an orgasnised way.
     */
    public function loadBody($page){
        require(VIEW_FOLDER.$page);
    }

}