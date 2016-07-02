<?php
class CSTRING{
    public function __construct(){}
    // HTMLエンティティのエスケープ
    public function html_escape($str, $flag = ENT_QUOTES, $encode = "UTF-8", $double_encode = true){  return htmlspecialchars($str, $flag, $encode, $double_encode);  }
    // 空文字の削除
    public function trim_space($str){  return strtr($str, array(" " => "", "　" => "", "\r" => "", "\n" => "", "\r\n"));  }
    // 文字数チェック
    public function is_length($str, $max, $small = null, $bool = false, $charset = 'UTF-8'){
        $ret_len = mb_strlen($str, $charset);
        if(!is_null($small)){
            $flag = ($ret_len <= $max && $ret_len >= $small)? true : false ;
        }else{
            $flag = ($ret_len <= $max)? true : false ;
        }
        return ($bool)? $ret_len: $flag;
    }
    // 空文字チェック
    public function is_space($str){  return $this->trim_space($str)? true : false; }
    // 数値チェック
    public function is_digit($num){  return ctype_digit($num)? true : false;  }
    // アルファベットのチェック
    public function is_alpha($alpha){  return ctype_alpha($alpha)? true : false;  }
    // アルファベットもしくは数値のチェック
    public function is_alnum($alnum){  return ctype_alnum($alnum)? true : false;  }
    // メールアドレスの正規表現チェック
    public function is_mailaddress($mail){  return preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)? true : false;  }
    // 電話番号チェック
    public function is_phonenumber($str){  return preg_match("/^0\d{9,10}$/", str_replace("-", "", $str));  } 
    // 郵便番号チェック
    public function is_zipcode($str){  return preg_match("/^\d{7}$/", str_replace("-", "", $str));  } 
}
