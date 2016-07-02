<?php

class CRESPONSE{
    
    // クライアントに返すHTMLなど格納
    private $content;
    
    // ステータスコードの格納
    // レスポンスがどのような状態にあるかを数字で表現
    private $status_code = '200';
    
    // Not Foundや Internal Server Errorなどステータスのテキストを格納
    private $status_text = 'OK';
    
    // HTTPヘッダを格納
    private $http_headers = array();
    
    
    /***************************************************************************
     *                      HTTPリクエストの送信
     ***************************************************************************/
    public function send(){
        header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);
        foreach($this->http_headers as $name => $value) {
            header($name . ': '. $value);
        }
        echo $this->content;
    }
    
    
    /***************************************************************************
     *                      コンテンツのセット
     ***************************************************************************/
    public function setContent($content){  $this->content = $content;  }
    
    
    /***************************************************************************
     *                      ステータスコードのセット
     ***************************************************************************/
    public function setStatusCode($status_code, $status_text = ''){
        $this->status_code = $status_code;
        $this->status_text = $status_text;        
    }
    
    
    /***************************************************************************
     *                      HTTPヘッダーのセット
     ***************************************************************************/
    public function setHttpHeader($name, $value){
        $this->http_headers[$name] = $value;
    }
    
}

