<?php
class CERRORPAGE extends CRESPONSE{
    
    protected $config;
    protected $view;
    protected $user404_path;
    
    public function __construct(){
        $this->config = CSTD::get_object("config");
        $this->view   = CSTD::get_object("view");
        $this->user404_path = $this->config->get_user_path()."/error/404.php";
    }
    
    public function default400(){
        
    }
    
    public function default404(Array $params = array()){
        $html = (file_exists($this->user404_path))? $this->view->get_buffer($this->user404_path, $params) : $this->content("404 Page Not Found.", "Page not found.");
        $this->sender(404, "Not Found", $html);
    }

    
    protected function sender($code, $text, $content, $header = array()){
        $this->setStatusCode($code, $text);
        $this->setContent($content);
        foreach($header as $key => $val){
            $this->setHttpHeader($key, $val);
        }
        $this->send();
        exit;
    }
    
    private function content($title, $message){
        return <<< EOF
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{$title}</title>
    </head>
    <body>
        <div style="width:60%; margin:3em auto;">
            <p style="font-weight:bold;">{$message}</p>
        </div>
    </body>
</html>
EOF;
    }
    
}
