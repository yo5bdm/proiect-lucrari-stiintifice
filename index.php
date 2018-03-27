<?php
//verificari de inceput
if(!session_start(['cookie_lifetime' => 86400])) die("Nu se poate crea sesiunea");
if(!is_writable(session_save_path())) die('Session path "'.session_save_path().'" is not writable for PHP!'); 
//ok pana aici, pornim autoloaderul si aplicatia
require_once 'framework/Autoload.php';
error_reporting(E_ALL); //de schimbat cand dam deploy
//print_r($_POST);
App::create();