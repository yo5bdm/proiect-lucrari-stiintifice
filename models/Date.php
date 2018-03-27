<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Date extends Model {
    
    private $userId=null;

    protected function getNextLucrareID() { //returneaza urmatorul ID, pentru inserare
        $id = 0;
        foreach($this->data['Date']['Lucrari'] as $lucrare) {
            if($lucrare['id']>$id) {
                $id = $lucrare['id'];
            }
        }
        return $id+1;
    }
    
    
    public function getListaLucrari() {
        if($this->userId != null) {
            //filtreaza dupa id
            $lucrari = array();
            foreach($this->data['Date']['Lucrari'] as $lucrare) {
                foreach($lucrare['autori'] as $autor) {
                    if($autor == $this->userId) {
                        array_push($lucrari, $lucrare);
                    }
                }
            }
            return $lucrari;
        }
        return $this->data['Date']['Lucrari'];
    }
    
    public function getListaDepartamente() {
        return $this->data['Date']['Departamente'];
    }
    
    public function salveazaLucrarea($lucrare) {
        $lucrare->setID($this->getID());
        array_push($this->data['Date']['Lucrari'], $lucrare->asArray());
    }
    
    public function userID($id) {
        $this->userId = $id;
        return $this;
    }

    protected function getID() {
        $id = 0;
        foreach($this->data['Date']['Lucrari'] as $lucrare) {
            if($lucrare['id'] > $id) $id = $lucrare['id'];
        }
        return $id+1;
    }

    public function insertData($data) {
        //unused
    }

}