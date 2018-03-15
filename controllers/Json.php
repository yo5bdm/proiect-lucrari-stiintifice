<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Json extends Controller {
    
    public $json = true;
    
    public function listalucrari() {
        $date = new Date();
        $data = $date->getListaLucrari();
        $this->setData($data);
    }
    
}