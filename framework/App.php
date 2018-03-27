<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class App {
    // ------------------------ STATIC ---------------------------------
    
    public static $app=null;
    
    public static function create() { //singleton create
        if(App::$app != null) {
            die("Nu se poate crea decat un singur obiect al clasei App.");
        }
        App::$app = new App();
        App::$app->run();
    }
    
    // ----------------------- NESTATIC --------------------------------
    public $route;
    public $post=null;
    public $settings;
    public $user;
    public $mesaj;
    
    private $havePost=false;
    
    private function __construct() {
        $this->settings = new Settings();
        $this->user = Loggeduser::get();
    }
    
    public function run() {
        $this->route = new Route();
        if(count($_POST)>0) {
            $this->havePost = true;
            $this->post = $_POST;
        } else {
            $this->havePost = false;
        }
        
        $contr = new $this->route->controller(); //instantiaza controllerul selectat
        $ac = $this->route->action;
        $contr->$ac($this->route->id,$this->route->parametrii); //executa actiunea selectata
        
    }
    
    public function isPost() {
        return $this->havePost;
    }
}