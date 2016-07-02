<?php
class CSESSION{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;
    
    /***************************************************************************
     *              セッションが開始していなければ開始させる
     ***************************************************************************/
    public function __construct($name){
        $http = CSTD::get_object("http");
        if($http->is_carrier() === 'MB') {
            ini_set('session.use_only_cookies' , '0');
            ini_set('session.use_cookies' , '0');
            ini_set('session.use_trans_sid', '1');
        }
        if(!self::$sessionStarted){
            session_name($name);
            session_start();
            self::$sessionStarted = true;
        }

    }
    
    /***************************************************************************
     *                      SESSIONの値セット
     ***************************************************************************/
    public function set($name, $value){  $_SESSION[$name] = $value;  }
    
    /***************************************************************************
     *                        SESSIONの値取得
     ***************************************************************************/
    public function get($name, $default = false){
        return (isset($_SESSION[$name]))? $_SESSION[$name] : $default ;
    }
    
    /***************************************************************************
     *                          SESSIONの削除
     ***************************************************************************/
    public function remove($name){  unset($_SESSION[$name]);  }
    
    /***************************************************************************
     *                          セッションのクリア
     ***************************************************************************/
    public function clear(){  $_SESSION = array();  }
    
    /***************************************************************************
     *                      セッションIDの書き換え
     ***************************************************************************/
    public function regenerate($destroy = true){
        if(!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);
            self::$sessionIdRegenerated = true;
        }
    }
}
