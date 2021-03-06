<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Lucrare extends Obiect {
    private $id;
    public $titlu;
    public $autori=array();
    public $keywords;
    public $bazededate = array();
    public $abstract;
    public $indexare;
    public $volum;
    public $pagini;
    public $conferinta;
    public $anulPublicarii;
    public $linkuri = array();
    public $linkLocal;
    public $citari=array();
    
    public function creare(
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
        array_push($this->autori,["id"=>App::$app->user->getId(),"nume"=>App::$app->user->getName()]);
    }
    
    public function set($id,$titlu,$autori,$keywords,$abstract,$volum,$pagini,$conferinta,$anulPublicarii,$link,$linkLocal,$citari,$links=array()) {
        $this->id = $id;
        $this->titlu = $titlu;
        $this->autori = $autori;
        $this->keywords = $keywords;
        $this->abstract = $abstract;
        $this->volum = $volum;
        $this->pagini = $pagini;
        $this->conferinta = $conferinta;
        $this->anulPublicarii = $anulPublicarii;
        $this->link = $link;
        $this->linkLocal = $linkLocal;
        $this->citari = $citari;
    }

    public function addAutor($id) {
        aray_push($this->autori,$id);
    }
    public function addCitare($citare) {
        aray_push($this->citari,$citare->asArray());
    }
    public function addLink($link) {
        array_push($this->linkuri, $link);
    }
    public function getID() {
        return $this->id;
    }
    public function setID($id) {
        $this->id = $id;
    }
}