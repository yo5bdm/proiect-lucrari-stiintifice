<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller {
    private $view;
    private $error404;
    public $json = false;
    public $admin = false;
    protected $permissions=array(); //
    
    public function __construct() {
        $this->view = new View();
        $this->error404 = false;
    }
    
    public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            if (!$this->checkPermissions($method)) {
                $method = "error404";
            }
        } else {
            $method = "error404";
        }
        return call_user_func_array(array($this, $method), $arguments);
    }
    
    protected function checkPermissions($method) {
        //return true;
        if($this->admin==true){
            if(App::$app->user->isAdmin()==true) return true;
            else return false;
        }
        if(count($this->permissions)>0 && in_array($method, $this->permissions)) { //daca e in array, e protejat
            if(App::$app->user->isLoggedIn()) return true;
            else return false;
        }
        return true;
    }
    
    public function setData($content) {
        $this->view->setData($content,$this->json);
    }
    
    public function __destruct() {
        if(!$this->error404) $this->view->generate();
    }
    
    public function redirect($to) {
        //$url = App::$app->settings->url.DS.App::$app->settings->index."?c=".$to['c']."&a=".$to['a'];
        $url = Helpers::generateUrl($to);
        header('Location: '.$url);
    }
    
    public function alert($mesaj) {
        $_SESSION['mesaj'] = $mesaj;
    }
    
    protected function error404() {
        $this->error404 = true;
        $this->view->error404();
    }
}