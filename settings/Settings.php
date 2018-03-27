<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Settings {
    public $layout = 'layout.php';
    public $numeAplicatie = "Lucrări Stiințifice";
    public $autori = "Erdei Rudolf, Lung Tudor";
    public $url ="http://localhost/lucraristiintifice";
    public $useIndex = false;
    public $index = "index.php?c=";
    public $webRoot = "web";
    public $appFolder = "lucraristiintifice"; //false daca nu e in subfolder
    public $cssFiles = array(
        'site'
    );
    public $jsFiles = array(
        'angular.min',
        'jquery-3.3.1.min',
        //'typeahead.bundle'
    );
    
    public $defaultRoute = array(
        "controller"=>"site",
        "action"=>"index"
    );
    
    public $meniuVizitator = array(
        "Acasa" => ["c"=>"site","a"=>"index"],
        "Login"=>["c"=>"site","a"=>"login"],
        "Inregistrare"=>["c"=>"site","a"=>"register"],
    );
    
    public $meniuLogat = array(
        "Acasa" => ["c"=>"site","a"=>"index"],
        "Lista lucrari" => ["c"=>"lucrari","a"=>"index"],
        "Adauga Lucrare"=>["c"=>"lucrari","a"=>"add"],
        "Logout" => ["c"=>"site","a"=>"logout"]
    );
    
    public $traducere = array(
        'username' => 'Nume de utilizator',
        'group' => 'Grup',
        'password' => 'Parola'
    );
    
    
    // NU MODIFICATI DUPA ACEST PUNCT NIMIC
    public function siteRoot() {
        if($this->useIndex==false) {
            return $this->url;
        } else {
            return $this->url.DS.$this->index;
        }
    }
    public function webRoot() {
        return $this->url.DS.$this->webRoot;
    }
    public function printCssFiles() {
        $ret ="";
        foreach($this->cssFiles as $file) {
            $ret .= "<link rel=\"stylesheet\" href=".$this->webRoot().DS."css".DS.$file.".css"." type=\"text/css\"/>";
        }
        $ret .= "<link rel=\"stylesheet\" href=\"".$this->webRoot().DS."bootstrap-3.3.7/dist/css/bootstrap.min.css\" type=\"text/css\"/>";
        return $ret;
    }
    public function printJsFiles() {
        $ret ="";
        foreach($this->jsFiles as $file) {
            $ret .= "<script src=".$this->url.DS.$this->webRoot.DS."js".DS.$file.".js"."></script>";
        }
        $ret .= "<script src=".$this->url.DS.$this->webRoot.DS."bootstrap-3.3.7/dist/js/bootstrap.min.js"."></script>";
        return $ret;
    }
    
}