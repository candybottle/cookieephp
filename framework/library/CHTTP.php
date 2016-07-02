<?php

class CHTTP{
   
    private static $uid;
    private static $user_agent;

    public function __construct() {}
    // http redirect
    public function redirect($url){  header("Location:".$url); exit;  }
    // POST access?
    public function is_post(){  return ($_SERVER["REQUEST_METHOD"] !== "POST")? false : true ;  }
    // GET access?
    public function is_get(){  return ($_SERVER["REQUEST_METHOD"] !== "GET")? false : true ;  }
    // Ajax access?
    public function is_ajax(){  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false ;  }
    // get host
    public function get_host(){  return (!empty($_SERVER['HTTP_HOST']))? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] ;  }
    // SSL access?
    public function is_ssl(){  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')? true : false;  }
    // is user agent
    public function is_carrier(){
    	$Agents = $_SERVER['HTTP_USER_AGENT'];
    	
        //NTT DoCoMo
        if (strpos("DoCoMo", $Agents)) {  $Judge = 1;  }
        //旧J-PHONE～vodafoneの2G
        else if (strpos("J-PHONE", $Agents)) {  $Judge = 1;  }
        //vodafoneの3G
        else if (strpos("Vodafone", $Agents)) {  $Judge = 1;  }
        //vodafoneの702MOシリーズ
        else if (strpos("MOT", $Agents)) {  $Judge = 1;  }
        //SoftBankの3G
        else if (strpos("SoftBank", $Agents)) {  $Judge = 1;  }
        //au (KDDI)
        else if (strpos("PDXGW", $Agents)) {  $Judge = 1;  }
        else if (preg_match("/UP\.Browser/", $Agents)) {  $Judge = 1;  }
        else if (preg_match("/KDDI\-[0-9a-zA-Z]+/", $Agents)) {  $Judge = 1;  }
        //ASTEL
        else if (strpos("ASTEL", $Agents)) {  $Judge = 1;  }
        //DDI Pocket
        else if (strpos("DDIPOCKET", $Agents)) {  $Judge = 1;  }
        
        //スマホ用の出力
        else if (strpos("iPhone", $Agents)) {  $Judge = 2;  }
        else if (strpos("Android", $Agents)) {  $Judge = 2;  }
        else if (strpos("BlackBerry", $Agents)) {  $Judge = 2;  }
        else if (strpos("iPod", $Agents)) {  $Judge = 2;  }
        else if (preg_match("/Windows\s?Phone/i", $Agents)) {  $Judge = 2;  }
        else if (strpos('URBANO', $Agents)) {  $Judge = 2;  }

        //それ以外 (PC)
        else {  $Judge = 3;  }
        
        //PCと携帯で振り分け
        switch($Judge){
            case 1: return "MB";
            case 2: return "SP";
            case 3: return "PC";
        }
    }
    // クローラー判別メソッド
    public function is_bot($agent = ""){
        if($agent === ""){  $agent = $_SERVER['HTTP_USER_AGENT'];  }
        
        $bool = false;
        if(
            strpos ( $agent,'Googlebot' ) !== false ||
            strpos ( $agent,'YPBot' ) !== false ||
            strpos ( $agent,'Yahoo! Slurp' ) !== false ||
            strpos ( $agent,'bingbot' ) !== false ||
            strpos ( $agent,'Yeti' ) !== false ||
            strpos ( $agent,'Baiduspider+' ) !== false ||
            strpos ( $agent,'Baiduspider' ) !== false ||
            strpos ( $agent,'Steeler' ) !== false ||
            strpos ( $agent,'ichiro/mobile goo' ) !== false ||
            strpos ( $agent,'ichiro' ) !== false ||
            strpos ( $agent,'hotpage.fr' ) !== false ||
            strpos ( $agent,'Feedfetcher-Google' ) !== false ||
            strpos ( $agent,'livedoor FeedFetcher' ) !== false ||
            strpos ( $agent,'ia_archiver' ) !== false ||
            strpos ( $agent,'YandexBot' ) !== false ||
            strpos ( $agent,'SISTRIX Crawler' ) !== false ||
            strpos ( $agent,'msnbot-media' ) !== false ||
            strpos ( $agent,'zenback bot' ) !== false ||
            strpos ( $agent,'Y!J-BRI' ) !== false ||
            strpos ( $agent,'TurnitinBot' ) !== false ||
            strpos ( $agent,'Google Desktop' ) !== false ||
            strpos ( $agent,'newzia crawler' ) !== false ||
            strpos ( $agent,'BaiduMobaider' ) !== false ||
            strpos ( $agent,'Y!J-BRJ/YATS crawler' ) !== false ||
            strpos ( $agent,'Seznam screenshot-generator' ) !== false ||
            strpos ( $agent,'SiteBot' ) !== false ||
            strpos ( $agent,'Purebot' ) !== false ||
            strpos ( $agent,'emBot-GalaBuzz/Nutch' ) !== false ||
            strpos ( $agent,'Search17Bot' ) !== false ||
            strpos ( $agent,'Toread-Crawler' ) !== false ||
            strpos ( $agent,'Tumblr' ) !== false ||
            strpos ( $agent,'DotBot' ) !== false ||
            strpos ( $agent,'Chilkat' ) !== false
            ) {
            $bool = true;
        }
        
        return $bool;
    }
    // リファラーの取得
    public function get_referer(){  return !empty($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']: '';  }
    // ブラウザの取得
    public function get_browser(){
        $classes = "";
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if(strstr($agent,"MSIE")){
            $classes .= "msie ";
            if(strstr($agent,"MSIE 6.0")) $classes .= "ie6 lt7 lt8 lt9";
            if(strstr($agent,"MSIE 7.0")) $classes .= "gt6 ie7 lt8 lt9";
            if(strstr($agent,"MSIE 8.0")) $classes .= "gt6 gt7 ie8 lt9";
            if(strstr($agent,"MSIE 9.0")) $classes .= "gt6 gt7 gt8 ie9";
        }
        else {
            $classes .= "noie ";
            if( strstr($agent,"Firefox")) {  $classes .= "firefox gecko";  }
            elseif( strstr($agent,"Safari")) {  $classes .= "safari webkit";  }
            elseif( strstr($agent,"Chrome")) {  $classes .= "Chrome webkit";  }
            elseif( strstr($agent,"Opera")) {  $classes .= "opera presto";  }
            //ここからは気休め。
            //AppleWebKit/534.30 (KHTML, like Gecko) なので先に記述
            elseif( stristr($agent,"WebKit")) {  $classes .= "webkit";  }
            //AppleWebKit/534.30 (KHTML, like Gecko) なので先に記述
            elseif( stristr($agent,"KHTML")) {  $classes .= "khtml";  }
            elseif(stristr($agent,"Gecko")) {  $classes .= "gecko";  }
            else {  $classes .= "other";  }
        }
        return $classes;
    }
    // Headersの取得
    public function get_all_headers(){
        foreach($_SERVER as $h=>$v){
            if(preg_match("/HTTP_(.+)/", $h, $hp))   $headers[$hp[1]] = $v;
        }
        return $headers;
    }
    // 固体識別番号の取得
    public function get_uid(){
        $head = (function_exists('getallheaders'))? getallheaders() : $this->get_all_headers() ;

        $user_agent = getenv('HTTP_USER_AGENT');
        
        // docomo
        if(preg_match('/^DoCoMo/', $user_agent, $m)){  $carrier = "docomo";  }
        // softbank
        else if(preg_match('/^(SoftBank|J\-PHONE|Vodafone)/', $user_agent, $m)){  $carrier = "softbank";  }   
        // au
        else if(preg_match('/^(UP\.Browser|ezweb|KDDI)/', $user_agent, $m)){  $carrier = "au";  }
        // other
        else{  $carrier = "other";  }
        
        $pattern = array(
            "docomo" => "/x(\_|\-)dcmguid/i", 
            "softbank" => "/x(\_|\-)jphone(\_|\-)uid/i", 
            "au" => "/x(\_|\-)up(\_|\-)subno/i",
            "other" => ""
        );
        
        $uid = "";
        if($pattern[$carrier] !== ""){
            foreach(array_keys($head) as $val){
                if(preg_match($pattern[$carrier],$val)){  $uid = $head[$val];  break;  }
            }
        }
        return $uid;
    }
    // ユーザーエージェント取得
    public function get_user_agent(){  return $_SERVER['HTTP_USER_AGENT'];  }
}