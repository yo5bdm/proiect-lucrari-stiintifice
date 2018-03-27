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
    private $json;
    
    private $viewFolder;
    
    public function __construct() {
        $this->title = ucfirst(App::$app->settings->numeAplicatie)." - ".ucfirst(App::$app->route->controller)." > ".ucfirst(App::$app->route->action);
        @$this->viewFolder = dirname(__FILE__,2) . "view";
        $this->json = false;
    }
    
    public function generate() {
        if(!$this->json) {
            $viewFile = $this->viewFolder.DS.App::$app->route->controller.DS.App::$app->route->action.".php";
            ob_start();
            include($viewFile);
            $this->content = ob_get_contents();
            ob_end_clean();
            require_once('view'.DS.App::$app->settings->layout);
        } else {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        }
        
    }
    
    public function setData($cont,$json) {
        $this->data = $cont;
        $this->json = $json;
    }
    
    public function setTitle($tit) {
        $this->title = ucfirst(App::$app->settings->numeAplicatie)." - ".$tit;
    }
    
    public function mesaj() {
        if(isset($_SESSION['mesaj'])){
            $mesaj = $_SESSION['mesaj'];
            unset($_SESSION['mesaj']);
            return '<div class="alert alert-warning" role="alert">'.$mesaj.'</div>';
        }
        return "";
    }
    
    public function error404() {
        $this->setTitle("Eroare");
        $viewFile = $this->viewFolder.DS."error404.php";
        ob_start();
        include($viewFile);
        $this->content = ob_get_contents();
        ob_end_clean();
        require_once('view'.DS.App::$app->settings->layout);
    }
}