<?php
class CDATABASE_DEF{
    
    protected $connection = false;
    protected $models = array();   
    
    public function __construct(){}
    
    /***************************************************************************
     *                      database connect
     ***************************************************************************/
    public function connect($db_name, $db_host, $user, $password, $options = array()){}
    
    /***************************************************************************
     *                      get db model object
     ***************************************************************************/
    public  function get($name){
        if(!isset($this->models[$name])){
            $model_class = $name . "Model";
            $model = new $model_class($this->connection);
            $this->models[$name] = $model;
        }
        return $this->models[$name];
    }
    
    protected function set_connection(){
        
    }

    public function __destruct(){
        unset($this->connection);
    }
    
}
