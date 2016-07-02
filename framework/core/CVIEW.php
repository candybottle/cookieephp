<?php

class CVIEW{
    private static $extension;
    private static $view_dir;

    private static $view            =   array();
    private static $view_template   =   "";
    private static $view_variable   =   array();
    private static $javascript      =   array();
    private static $css             =   array();



    /***************************************************************************
     *                      output buffer
     ***************************************************************************/
    private static function render($_____view_____ = array(), $_____variable_____ = array()){
        if(!empty($_____variable_____)){  extract($_____variable_____);  }
        unset($_____variable_____);

        if(empty($_____view_____)){  return "";  }
        ob_start();
        ob_implicit_flush(0); // auto flush off
        if(is_array($_____view_____)){
            foreach($_____view_____ as $_____val_____){
                include self::$view_dir . $_____val_____ . "." . self::$extension;
            }
        }else{
            include self::$view_dir . $_____view_____ . "." . self::$extension;
        }

        return ob_get_clean();
    }


    /***************************************************************************
     *                      set variable
     ***************************************************************************/
    private static function setVariable($name, $value = "", $multi = false){
        if($multi === false){
            if(is_array($name)){
                foreach($name as $key => $val){  self::$view_variable[$key] = $val;  }
            }else{
                self::$view_variable[$name] = $value;
            }
        }else{
            if(is_array($name)){
                foreach($name as $key => $val){  self::$view_variable[$key][] = $val;  }
            }else{
                self::$view_variable[$name][] = $value;
            }
        }
    }


    /***************************************************************************
     *                      auto create javascript format
     ***************************************************************************/
    private static function setJavascriptFile($file, $tag = false, $cache = false){
        switch($tag){
            case 1:
                $ext = ($cache)? ".js?".(new DateTime())->format("YmdHis") : ".js" ;
                $a = '';
                $b = '';
                break;
            case 2:
                $ext = '';
                $a = '<script type="text/javascript">'.PHP_EOL;
                $b = '</script>'.PHP_EOL;
                break;
            case 3:
                $ext = '';
                $a = '';
                $b = '';
                break;
            case 0:
            case false:
            default:
                $ext = ($cache)? ".js?".(new DateTime())->format("YmdHis") : ".js" ;
                $a = '<script type="text/javascript" src="';
                $b = '"></script>';
                break;
        }
        if(!is_array($file)){
            self::$javascript[] = $a.$file.$ext.$b;
        }else{
            foreach($file as $val){  self::$javascript[] = $a.$val.$ext.$b;  }
        }
    }


    /***************************************************************************
     *                          auto create css format
     ***************************************************************************/
    private static function setCssFile($file, $tag = false, $cache = false){
        switch($tag){
            case 1:
                $ext = ($cache)? ".css?".(new DateTime())->format("YmdHis") : ".css" ;
                $a = '';
                $b = '';
                break;
            case 2:
                $ext = '';
                $a = '<style type="text/css">'.PHP_EOL;
                $b = '</style>'.PHP_EOL;
                break;
            case 3:
                $ext = '';
                $a = '';
                $b = '';
                break;
            case 0:
            case false:
            default:
                $ext = ($cache)? ".css?".(new DateTime())->format("YmdHis") : ".css" ;
                $a = '<link rel="stylesheet" type="text/css" href="';
                $b = '" />';
                break;
        }
        if(!is_array($file)){
            self::$css[] = $a.$file.$ext.$b;
        }else{
            foreach($file as $val){  self::$css[] = $a.$val.$ext.$b;  }
        }
    }

    // output buffer
    private static function buffer($path, Array $data = array()){
        if(!empty($data)){
            extract($data);
        }
        ob_start();
        ob_implicit_flush(0); // auto flush off
        include $path;
        return ob_get_clean();
    }




    /*---------------------------------  dynamic  ------------------------------*/
    public function __construct() {
        $config = CSTD::get_object("config");
        self::$view_dir  = $config->get_view_path();
        self::$extension = $config->get_template_extension();
    }

    public function output(){
        $view       =   self::$view;
        $template   =   self::$view_template;
        $variable   =   self::$view_variable;
        $js         =   self::$javascript;
        $css        =   self::$css;

        // template
        if($template){
            $content = self::render($view, $variable);
            $view       =   self::$view;
            $template   =   self::$view_template;
            $variable   =   self::$view_variable;
            $js         =   self::$javascript;
            $css        =   self::$css;
            return self::render($template, array_merge($variable, array("_____content_____" => $content, "_____javascript_____" => $js, "_____css_____" => $css)));

        // default
        }else{
            return self::render($view, array_merge($variable, array("_____javascript_____" => $js, "_____css_____" => $css)));
        }
    }


    public function set_view($path){  self::$view[] = $path;  }
    public function set_template($path){  self::$view_template = $path;  }
    public function set_variable($name, $value = "", $multi = false){  self::setVariable($name, $value, $multi);  }
    public function set_javascript_file($file, $tag = false, $cache = false){  self::setJavascriptFile($file, $tag, $cache);  }
    public function set_css_file($file, $tag = false, $cache = false){  self::setCssFile($file, $tag, $cache);  }
    public function get_content(){  return self::output();  }
    public function get_buffer($path, Array $data = array()){  return self::buffer($path, $data);  }
}