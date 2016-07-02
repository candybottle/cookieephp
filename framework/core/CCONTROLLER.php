<?php

class CCONTROLLER{
    protected $config    =   false;
    protected $session   =   false;
    protected $db        =   false;
    protected $view      =   false;
    protected $loader    =   false;
    protected $response  =   false;
    protected $errorpage =   false;
    
    private $_objname = array();
    
    public function __construct(){
        $_object = CSTD::get_object();
        foreach($_object as $_obj_name => $_obj){
            $this->$_obj_name = $_obj;
            $this->_objname[] = $_obj_name;
        }
    }
    
    /*##########################################################################
     *                             Database
     *##########################################################################*/

    // db switch
    protected function db_connect($db_name, $db_host = "", $user = "", $password = "", $options = array()){
        if(is_array($db_name)){
            $this->db->connect($db_name[0], $db_name[1], $db_name[2], $db_name[3], $db_name[4]);
        }else{
            $this->db->connect($db_name, $db_host, $user, $password, $options);
        }
    }
    
    
    public function __destruct() {
        foreach($this->_objname as $_objname){
            CSTD::set_object($_objname, $this->$_objname);
        }
    }

}
