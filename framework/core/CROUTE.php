<?php

class CROUTE{
    
    private $config;
    
    public function __construct() {
        $this->config = CSTD::get_object("config");
    }
    
    
    /***************************************************************************
     *                          ルートマッチングの実行
     ***************************************************************************/
    public function routing_resolve($url){
        $routing = $this->compile_routes($this->config->get_routing());
        
        if('/' !== substr($url, 0, 1)) { $url = '/' . $url; }

        $controller = 0; $action = 1; $param = 2;
        $params = array($controller => "", $action => "", $param => array());

        if(empty($routing)){
            $a = explode("/", $url);
            $params[$controller] = (!isset($a[1]) || $a[1] === "")? $this->config->get_default_controller_name() : $a[1] ;
            $params[$action]     = (!isset($a[2]) || $a[2] === "")? $this->config->get_default_action_name() : $a[2] ;
            unset($a[0]);
            unset($a[1]);
            unset($a[2]);
            if(!empty($a)){
                foreach($a as $val){  $params[$param][] = $val;   }
            }
        }else{
            if(isset($routing[$url])){ 
                $params[$controller]    =   $routing[$url][$controller];
                $params[$action]        =   $routing[$url][$action];
                $params[$param]         =   array();
            }else{
                foreach($routing as $pattern => $par) {
                    // パスインフォとマッチすれば、マッチした部分と
                    // すでに設定されてある配列を結合し、リターン
                    if(preg_match('#^' . $pattern . '$#', $url, $matches)) {
                        $params[$controller]    =   $par[$controller];
                        $params[$action]        =   $par[$action];
                        $params[$param]         =   $matches;
                        break;
                    }
                }
            }
        }
        return ($params[$controller] === "" || $params[$action] === "") ? false : $params ;
    }
    
    
    /***************************************************************************
     *                  パラメータを正規表現式に変換
     ***************************************************************************/
    public function compile_routes($definitions){
        $routes = array();       
        foreach($definitions as $url => $params) {
            $pattern = $url;
            if(false !== strpos($url, ":")){
                $tokens = explode("/", ltrim($url, '/'));
                foreach($tokens as $key => $token) {
                    if(0 === strpos($token, ":")) {
                        $name  = substr($token, 1);
                        $token = "(?P<" . $name . ">[^/]+)";
                    }
                    $tokens[$key] = $token;
                }
                $pattern = '/'. implode('/', $tokens);
            }
            $routes[$pattern] = $params;
        }
        return $routes;
    }
    
    
    /***************************************************************************
     *                          リクエストURLの取得
     ***************************************************************************/
    public function get_request_url(){
        $request_uri = $_SERVER['REQUEST_URI'];
        if(false !== ($pos = strpos($request_uri, '?'))) { $request_uri = substr($request_uri, 0, $pos); }
        return (string)substr($request_uri, strlen($this->get_base_url()));
    }
    
    
    /***************************************************************************
     *                          ベースURLの取得
     ***************************************************************************/
    public function get_base_url(){
        return (strpos($_SERVER["REQUEST_URI"], $_SERVER['SCRIPT_NAME']) === 0) ? $_SERVER['SCRIPT_NAME'] : "/" ;
    }
    
    
    
}