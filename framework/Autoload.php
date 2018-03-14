<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("DS","/");
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