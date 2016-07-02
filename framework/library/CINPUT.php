<?php
class CINPUT{
    public function __construct(){}
    // POSTの取得（引数：post名、存在しない場合の返り値）
    public function post($name = "", $return = false){  return (isset($_POST[$name]))? $_POST[$name] : $return ;  }
    // GETの取得（引数：get名、存在しない場合の返り値）
    public function get($name = "", $return = false){  return (isset($_GET[$name]))? $_GET[$name] : $return ;  }
}