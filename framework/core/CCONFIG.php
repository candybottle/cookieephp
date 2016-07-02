<?php
class CCONFIG{
    
    private static $final = false;
    
    private static $path_framework;
    private static $path_user;
    private static $routing;
    private static $session_name;
    private static $db_driver;
    private static $db_name;
    private static $db_host;
    private static $db_user;
    private static $db_pass;
    private static $db_option;
    private static $extension;
    private static $model_path;
    private static $view_path;
    private static $controller_path;
    private static $autoload_dir;
    private static $default_controller;
    private static $default_action;
    private static $debug_mode;
    
    private $config;
    
    
    public function __construct(Array $config = array()){
        if(!self::$final){
            $this->config = $config;
            $this->initialize();
            self::$final = true;
        }
    }
    
    private function initialize(){
        $this->set_framework_path();
        $this->set_user_path();
        $this->set_routing();
        $this->set_session_name();
        $this->set_db_driver();
        $this->set_db_name();
        $this->set_db_host();
        $this->set_db_user();
        $this->set_db_pass();
        $this->set_db_option();
        $this->set_extension();
        $this->set_model_path();
        $this->set_view_path();
        $this->set_controller_path();
        $this->set_autoload_dir();
        $this->set_default_controller();
        $this->set_default_action();
        $this->set_debug_mode();
    }
    
    private function set_framework_path(){
        self::$path_framework = (!empty($this->config["path_framework"]))? $this->config["path_framework"] : "" ;
    }
    private function set_user_path(){
        self::$path_user = (!empty($this->config["path_user"]))? $this->config["path_user"] : "" ;
    }
    private function set_routing(){
        self::$routing = (!empty($this->config["route"]))? $this->config["route"] : array();
    }
    private function set_session_name(){
        self::$session_name = (!empty($this->config["session_name"]))? $this->config["session_name"] : "" ;
    }
    private function set_db_driver(){
        if(!empty($this->config["db_driver"])){
            $dbdriver = strtolower($this->config["db_driver"]);
            switch($dbdriver){
                case "pdo"   :
                case "mysql" :
                case "mysqli":
                    self::$db_driver = $dbdriver;
                    break;
                default:
                    self::$db_driver = "pdo";
                    break;
            }
        }else{
            self::$db_driver = "pdo";
        }
    }
    private function set_db_name(){
        self::$db_name = (!empty($this->config["db_name"]))? $this->config["db_name"] : "" ;
    }
    private function set_db_host(){
        self::$db_host = (!empty($this->config["db_host"]))? $this->config["db_host"] : "" ;
    }
    private function set_db_user(){
        self::$db_user = (!empty($this->config["db_user"]))? $this->config["db_user"] : "" ;
    }
    private function set_db_pass(){
        self::$db_pass = (!empty($this->config["db_pass"]))? $this->config["db_pass"] : "" ;
    }
    private function set_db_option(){
        self::$db_option = (!empty($this->config["db_option"]) && is_array($this->config["db_option"]))? $this->config["db_option"] : array() ;
    }
    private function set_extension(){
        self::$extension = (!empty($this->config["template_extension"]))? $this->config["template_extension"] : "php" ;
    }
    private function set_model_path(){
        self::$model_path = self::$path_user . "/model/";
    }
    private function set_view_path(){
        self::$view_path = self::$path_user . "/view/";
    }
    private function set_controller_path(){
        self::$controller_path = self::$path_user . "/controller/";
    }
    private function set_autoload_dir(){
        self::$autoload_dir = array(
            self::$path_framework . '/core/',
            self::$path_framework . '/library/',
            self::$path_framework . '/module/',      
            self::$model_path,
        );
        if(is_array($this->config["autoload"]) && !empty($this->config["autoload"])){
            foreach($this->config["autoload"] as $val){ self::$autoload_dir[] = self::$path_user . "/" . $val . "/"; }
        }
    }
    private function set_default_controller(){
        self::$default_controller = (!empty($this->config["default_controller"]))? $this->config["default_controller"] : "index" ;
    }
    private function set_default_action(){
        self::$default_action = (!empty($this->config["default_action"]))? $this->config["default_action"] : "index" ;
    }
    private function set_debug_mode(){
        self::$debug_mode = (!empty($this->config["debug_mode"]))? true : false ;
    }
    
    public function get_framework_path(){  return self::$path_framework;  }
    public function get_user_path(){  return self::$path_user;  }
    public function get_routing(){  return self::$routing;  }
    public function get_session_name(){  return self::$session_name;  }
    public function get_db_driver(){  return self::$db_driver;  }
    public function get_db_name(){  return self::$db_name;  }
    public function get_db_host(){  return self::$db_host;  }
    public function get_db_user(){  return self::$db_user;  }
    public function get_db_pass(){  return self::$db_pass;  }
    public function get_db_option(){  return self::$db_option;  }
    public function get_template_extension(){  return self::$extension;  }
    public function get_model_path(){  return self::$model_path;  }
    public function get_view_path(){  return self::$view_path;  }
    public function get_controller_path(){  return self::$controller_path;  }
    public function get_autoload_dir(){  return self::$autoload_dir;  }
    public function get_default_controller_name(){  return self::$default_controller;  }
    public function get_default_action_name(){  return self::$default_action;  }
    public function get_debug_mode(){  return self::$debug_mode;  }
    
}
