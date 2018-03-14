<?php
define("DS","/");
error_reporting(E_ALL);
spl_autoload_register(function ($className) {
    $dirs = array(
        'framework',
        'controllers',
        'models',
        'settings'
    );
    foreach($dirs as $directory) {
        $file = $directory.DS.$className.".php";
        if(file_exists($file)) {
            require_once($file);
            return true;
        }
    }  
    return false;    
});

App::create();