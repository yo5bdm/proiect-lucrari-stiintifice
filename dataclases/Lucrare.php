<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Lucrare {
    private $id;
    private $titlu;
    private $autori=array();
    private $abstract;
    private $volum;
    private $pagini;
    private $conferinta;
    private $anulPublicarii;
    private $link;
    private $linkLocal;
    private $citari=array();
    private $bibliografie=array();
    
    public function __construct( //constructor de creare element nou
            $titlu, $abstract, $volum, $pagini, 
            $conferinta, $anulPublicarii, $link, $linkLocal
            ) {
        $this->titlu = $titlu;
        $this->abstract = $abstract;
        $this->volum = $volum;
        $this->pagini = $pagini;
        $this->conferinta = $conferinta;
        $this->anulPublicarii = $anulPublicarii;
        $this->link = $link;
        $this->linkLocal = $linkLocal;
        array_push($this->autori,App::$app->user->getId());
    }
    
    

    public function asArray() {
        $vars = get_class_vars(get_class($this));
        $ret = array();
        foreach($vars as $nume => $valoare) {
            $ret[$nume] = $this->$nume;
        }
        return $ret;
    }
    
    public function addAutor($id) {
        aray_push($this->autori,$id);
    }
    
    public function addCitare($txt) {
        aray_push($this->citari,$txt);
    }
    
    public function addBibliografie($txt) {
        aray_push($this->bibliografie,$txt);
    }
}