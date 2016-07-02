<?php

class CAPPLICATION{
    /*--------------------------------------------------------------------------
     *              Application RUN!!
     *--------------------------------------------------------------------------*/
    public static function run($config = array()){
        $config_file = (isset($config["path_framework"]))? $config["path_framework"] : "";
        self::file_include($config_file."/core/CCONFIG.php");
        self::file_include($config_file."/core/CLOADER.php");
        self::file_include($config_file."/core/CSTD.php");
        
        $conf   = new CCONFIG($config);
        $loader = new CLOADER();
        
        ini_set("display_errors", ($conf->get_debug_mode())?1:0);
        
        $loader->registerDir($conf->get_autoload_dir());
        $loader->register();

        CSTD::set_object("config", $conf);
        CSTD::set_object(array(
            // core
            "loader"    => $loader,
            "response"  => new CRESPONSE(),
            "view"      => new CVIEW(),
            
            // module
            "errorpage" => new CERRORPAGE(),
            
            // library
            "input"     => new CINPUT(),
            "http"      => new CHTTP(),
            "string"    => new CSTRING(),
        ));
        
        $errpage  = CSTD::get_object("errorpage");
        $response = CSTD::get_object("response");
        $view     = CSTD::get_object("view");
                
        // DataBase
        $dbdriver = strtoupper($conf->get_db_driver());
        if(!class_exists("CDATABASE")){
            class_alias("CDATABASE_".$dbdriver, "CDATABASE");
        }
        if(!class_exists("CMODEL")){
            class_alias("CMODEL_".$dbdriver, "CMODEL");
        }
        
        $db_info = array(
            $conf->get_db_name(),
            $conf->get_db_host(),
            $conf->get_db_user(),
            $conf->get_db_pass(),
            $conf->get_db_option()
        );

        if(!empty($db_info[0]) && !empty($db_info[1]) && !empty($db_info[2])){
            $db = new CDATABASE();
            $db->connect($db_info[0], $db_info[1], $db_info[2], $db_info[3], $db_info[4]);
            CSTD::set_object("db", $db);
        }else{
            CSTD::set_object("db", new CDATABASE());
        }   

        
        // session
        $session = $conf->get_session_name();
        if(!empty($session)){
            CSTD::set_object("session", new CSESSION($session));
        }
        
        $route    = new CROUTE();
        // routing
        $params = $route->routing_resolve($route->get_request_url());
        
        // controller or action empty check
        if(!$params){
            $errpage->default404();
        }else{
            $controller = self::findController($params[0]);
        }
        
        if(!$controller){
            $errpage->default404();
        }else{
            $action = self::controllerRun($controller, $params[1], $params[2]);
        }
        
        if(!$action){
            $errpage->default404();
        }

        $response->setContent($view->output());
        $response->send();
    }
    
    /***************************************************************************
     *                コントローラーを検索し、存在すれば読み込む
     ***************************************************************************/
    private static function findController($controller_name){
        $controller_class = ucfirst($controller_name) . "Controller";
        // クラスが読み込み可能であるかそうでないかを調べる
        if(!class_exists($controller_class)) {
            $config = CSTD::get_object("config");
            $controller_file = $config->get_controller_path() . $controller_class . '.php';
            if(!is_readable($controller_file)){  return false;  }
            include_once $controller_file;
            if(!class_exists($controller_class)) { return false; }
        }
        return new $controller_class();
    }
    
    /***************************************************************************
     *                 コントローラーのメソッドが存在すれば実行
     ***************************************************************************/
    private static function controllerRun(&$controller, $action, $params = array()){        
        $action_method = $action . "Action";
        // アクションMethodが存在するかどうか
        if(!method_exists($controller, $action_method)){  return false;  }
        $controller->$action_method($params);
        $controller = null;
        return true;
    }
    
    // file include
    private static function file_include($path){
        if(!file_exists($path)){
            echo "File not found.　".$path;
            exit;
        } else if(!is_readable($path)){
            echo "File can not be read.　".$path;
            exit;
        }
        include $path;
    }
}
