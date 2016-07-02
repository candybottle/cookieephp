<?php
class IndexController extends CCONTROLLER{
    
    public function __construct() {
        parent::__construct();
    }

    /*     Sample Top Page    */
    public function indexAction(){
        $this->view->set_view("index");
    }
    
}