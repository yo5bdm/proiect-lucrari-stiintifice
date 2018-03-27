<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Json extends Controller {
    
    public $json = true;
    
    private function getJson() {
        return json_decode(file_get_contents('php://input'),true);
    }
    
    public function listalucrari() {
        $date = new Date();
        $data = $date->getListaLucrari();
        $this->setData($data);
    }
    
    public function utilizator($id) {
        $date = new Date();
        $data = $date->userId($id)->getListaLucrari();
        $this->setData($data);
    }
    
    public function listauseri() {
        $date = new Users();
        $data = $date->getData();
        $this->setData($data);
    }
    
    public function getuser($nume) {
        $users = new Users();
        $u = $users->getByName($nume);
        $this->setData($u);
    }
    
    public function getusers() {
        $users = new Users();
        $u = $users->getNameList();
        $this->setData($u);
    }
    
    public function savelucrare() {
        if($this->getJson()!=NULL) {
            $json = $this->getJson();
            $lucrare = new Lucrare();
            $lucrare->fromArray($json['datele']);
            $lucrareModel = new Date();
            $lucrareModel->salveazaLucrarea($lucrare);
            $this->setData('1');
        } else {
            $this->setData('0');
        }
    }
    
}