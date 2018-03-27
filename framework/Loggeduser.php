<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Loggeduser extends User {
    private $loggedIn=false;
    
    public function isLoggedIn() {
        return $this->loggedIn;
    }
    
    public function __construct() {
        $this->loggedIn=false;
    }
    
    public function detalii($user) {
        $arr = array('id', 'nume', 'prenume', 'username','group','admin');
        foreach($arr as $a) {
            $this->$a = $user->$a;
        }
    }
    
    //static methods
    public static function login($user) {
        App::$app->user->detalii($user);
        App::$app->user->loggedIn = true;
        $arr = array('id', 'nume', 'prenume', 'username','group','admin');
        foreach($arr as $a) {
            $_SESSION['user'][$a] = $user->$a;
        }
        return true;
    }
    
    public static function get() {
        $user = new Loggeduser();
        if(isset($_SESSION['user'])) {
            $u = new User();
            $arr = array('id', 'nume', 'prenume', 'username','group','admin');
            foreach($arr as $a) {
                $u->$a = $_SESSION['user'][$a];
            }
            $user->detalii($u);
            $user->loggedIn = true;
        } 
        return $user;
    }
    
    public static function logout() {
        session_unset(); 
        session_destroy(); 
    }
    
    public function getName() {
        return $this->nume . " " . $this->prenume;
    }
}