<?php

class CLOADER{
    private $dirs;
    
    /***************************************************************************
     *                          autoload method set
     ***************************************************************************/
    public function register(){ spl_autoload_register(array($this, 'loadClass')); }
    
    /***************************************************************************
     *                          autoload directry
     ***************************************************************************/
    public function registerDir($dir){
        if(is_array($dir)) {
            foreach($dir as $value) {
                $this->listDirs($value);
            }
        } else {
            $this->listDirs($dir);
        }
    }
    
    /***************************************************************************
     *                          autoload method
     ***************************************************************************/
    private function loadClass($class_name){
        $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
        foreach($this->dirs as $dir) {
            $class_file = $dir . $class_name . '.php';
            if(is_readable($class_file)) { include $class_file; return ; }
        }
    }
    
    
    /***************************************************************************
     *                          directry list up
     ***************************************************************************/
    private function listDirs($dir){
        $files = scandir($dir);
        $this->dirs[] = $dir;
        foreach($files as $file){
            if($file === '.' || $file === '..') { continue; }
            if(!is_dir($dir.$file)) {  continue;  }
            $this->listDirs($dir.$file."/");
        }
    }
    
}