<?php
if(!session_start([
    'cookie_lifetime' => 86400,
])) die("Nu se poate crea sesiunea");
if (!is_writable(session_save_path())) {
    die('Session path "'.session_save_path().'" is not writable for PHP!'); 
}
require_once 'framework/Autoload.php';
error_reporting(E_ALL);
App::create();
//https://stackoverflow.com/questions/8976930/php-session-not-saving