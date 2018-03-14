<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View {
    public $data;
    public $content;
    public $title;
    
    public function __construct() {
        $this->title = ucfirst(App::$app->settings->numeAplicatie)." - ".ucfirst(App::$app->controller)." > ".ucfirst(App::$app->action);
    }
    
    public function generate() {
        $viewFile = "view".DS.App::$app->controller.DS.App::$app->action.".php";
        ob_start();
        include($viewFile);
        $this->content = ob_get_contents();
        ob_end_clean();
        require_once('view'.DS.App::$app->settings->layout);
    }
    
    public function setData($cont) {
        $this->data = $cont;
    }
    
    public function setTitle($tit) {
        $this->title = $tit;
    }
    
    public function mesaj() {
        if(isset($_SESSION['mesaj'])){
            $mesaj = $_SESSION['mesaj'];
            unset($_SESSION['mesaj']);
            return '<div class="alert alert-warning" role="alert">'.$mesaj.'</div>';
        }
        return "";
    }
}