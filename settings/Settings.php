<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Settings {
    public $layout = 'layout.php'; //layout used by app
    public $numeAplicatie = "Lucrări Stiințifice"; //name printed on menu bar
    public $autori = "Erdei Rudolf, Lung Tudor";
    public $url ="http://localhost/lucraristiintifice"; //used in link generation
    public $useIndex = false; //if htacces is enabled, don't use $index in link generation
    public $index = "index.php?c="; //
    public $webRoot = "web";
    public $appFolder = "/lucraristiintifice/"; //string gol daca nu e in subfolder
    
    public $debug = true;
    
    public $cssFiles = array(
        'site'
    );
    public $jsFiles = array(
        'angular.min',
        'jquery-3.3.1.min',
        'functii',
        'angular-chart.min'
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
    
    public $meniuAdmin = array(
        "Acasa" => ["c"=>"site","a"=>"index"],
        "Lista lucrari" => ["c"=>"lucrari","a"=>"index"],
        "Adauga Lucrare"=>["c"=>"lucrari","a"=>"add"],
        "Admin"=>['c'=>'admin','a'=>'index'],
        "Unitate"=>['c'=>'unitate','a'=>'index'],
        "Logout" => ["c"=>"site","a"=>"logout"]
        
    );
    
    public $traducere = array(
        'username' => 'Numele de utilizator',
        'group' => 'Grupul',
        'password' => 'Parola',
        'urlLocal' => 'Adresă URL locală',
        'urlRemote' => 'Adresă URL remote'
    );
    
    
    // NU MODIFICATI DUPA ACEST PUNCT NIMIC
    public function __construct() {
        if($this->debug == true) { //enable debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
    }
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
        $ret .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>';
        foreach($this->jsFiles as $file) {
            $ret .= "<script src=".$this->url.DS.$this->webRoot.DS."js".DS.$file.".js"."></script>";
        }
        $ret .= "<script src=".$this->url.DS.$this->webRoot.DS."bootstrap-3.3.7/dist/js/bootstrap.min.js"."></script>";
        
        return $ret;
    }
    
}