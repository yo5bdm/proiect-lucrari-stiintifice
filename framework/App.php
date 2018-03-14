<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class App {
    public static $app=null;
    
    public $controller=null;
    public $action=null;
    public $post=null;
    public $settings;
    public $user;
    
    private $havePost;
    
    public function isPost() {
        return $this->havePost;
    }

    public static function create() {
        if(App::$app != null) {
            die("Nu se poate crea decat un singur obiect al clasei App.");
        }
        App::$app = new App();
        App::$app->run();
    }
    
    private function __construct() {
        session_start();
        $this->settings = new Settings();
        $user = new User;
        User::get();
    }
    
    public function run() {
        if(isset($_GET['c'])) {
            $this->controller = $_GET['c'];
        } else {
            $this->controller = $this->settings->defaultRute['controller'];
        }
        if(isset($_GET['a'])) {
            $this->action = $_GET['a'];
        } else {
            $this->action = $this->settings->defaultRute['action'];
        }
        if(isset($_POST)) {
            $this->havePost = true;
            $this->post = $_POST;
        } else {
            $this->havePost = false;
        }
        if($this->action == null) {
            $this->action = 'index';
        }
        if($this->controller != null) {
            $contr = new $this->controller();
            $ac = $this->action;
            $contr->$ac();
        }
    }
}