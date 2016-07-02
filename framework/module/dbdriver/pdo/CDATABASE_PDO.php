<?php
class CDATABASE_PDO extends CDATABASE_DEF{

    public function __construct(){  parent::__construct();  }
    
    /***************************************************************************
     *                      database connect
     ***************************************************************************/
    public function connect($db_name, $db_host, $user, $password, $options = array()){
        try{
            $con = new PDO(  'mysql:dbname=' . $db_name . ';host=' . $db_host, $user, $password, $options  );
            $con->query("SET NAMES UTF8");
            // Error, Exception
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->connection = $con;

        }catch(PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
    }
    public function __destruct(){  parent::__destruct();  }
    
}
