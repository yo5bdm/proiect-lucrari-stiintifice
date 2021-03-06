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
    public function getListaLucrari($all = false) {
        if($this->userId != null && $all == false) {
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
    public function getListaGrupuri() {
        return $this->data['Date']['Grupuri'];
    }
    public function salveazaLucrarea($lucrare) {
        $lucrare->setID($this->getID());
        array_push($this->data['Date']['Lucrari'], $lucrare->asArray());
    }
    public function stergeLucrarea($idLucrare) {
        $lucrari = $this->data['Date']['Lucrari'];
        for($i=0;$i<count($lucrari);$i++) {
            if($lucrari[$i]['id'] == $idLucrare) {
                array_splice($this->data['Date']['Lucrari'], $i, 1); //sterg din array si refac numerotarea
                return true;
            }
        }
        return false;
    }
    public function getLucrarea($idLucrare) {
        $lucrari = $this->data['Date']['Lucrari'];
        for($i=0;$i<count($lucrari);$i++) {
            if($lucrari[$i]['id'] == $idLucrare) {
                return $lucrari[$i];
            }
        }
        return false;
    }
    public function actualizeazaLucrarea($lucrare) {
        $lucrari = $this->data['Date']['Lucrari'];
        for($i=0;$i<count($lucrari);$i++) {
            if($lucrari[$i]['id'] == $lucrare->getID()) {
                $this->updateLucrare($i, $lucrare);
                return true;
            }
        }
        return false;
    }

    public function getListaDepartamente() {
        return $this->data['Date']['Departamente'];
    }
    
    public function getUnitate() {
        $ret = array();
        $ret['departamente'] = $this->data['Date']['Departamente'];
        $ret['grupuri'] = $this->data['Date']['Grupuri'];
        $ret['facultati'] = $this->data['Date']['Facultati'];
        return $ret;
    }
    public function salveazaUnitate($json) {
        $this->data['Date']['Departamente'] = $json['datele']['departamente'];
        $this->data['Date']['Facultati'] = $json['datele']['facultati'];
        $this->data['Date']['Grupuri'] = $json['datele']['grupuri'];
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
    public function updateCategorii($cat) {
        $this->data['Date']['Categorii'] = $cat;
    }
    public function updateDBs($dbs) {
        $this->data['Date']['BazeDeDate'] = $dbs;
    }
    public function getCategorii() {
        return $this->data['Date']['Categorii'];
    }
    public function getDBs() {
        return $this->data['Date']['BazeDeDate'];
    }

    // METODE PRIVATE
    private function updateLucrare($index, $lucrare) {
        $vars = array(
            'titlu',
            'autori',
            'keywords',
            'abstract',
            'bazededate',
            'indexare',
            'volum',
            'pagini',
            'conferinta',
            'anulPublicarii',
            'linkuri',
            'linkLocal',
            'citari'
        );
        foreach($vars as $v) {
            $this->data['Date']['Lucrari'][$index][$v] = $lucrare->$v;
        }
    }
}