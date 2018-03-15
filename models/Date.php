<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Date extends Model {
    
    public function insertData($data) {
        
    }

    protected function getID() { //returneaza urmatorul ID, pentru inserare
        
    }
    
    
    public function getListaLucrari() {
        //print_r($this->data);
        return $this->data['Date']['Lucrari']; //['Lucrari']
    }
    
    public function getListaDepartamente() {
        return $this->data['Date']['Departamente'];
    }
    
    public function salveazaLucrarea($lucrare) {
        array_push($this->data['Date']['Lucrari'], $lucrare->asArray());
    }
}