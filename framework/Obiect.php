<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Obiect {
    
    public function getSettings($text) {
        if(!isset($this->settings)) return NULL;
        if(array_key_exists($text,$this->settings)) {
            return $this->settings[$text];
        } else return NULL;
    }
    
    public function asArray() {
        $vars = get_object_vars($this);
        $ret = array();
        foreach($vars as $nume => $valoare) {
            $ret[$nume] = $this->$nume;
        }
        $ret['id'] = $this->getID();
        return $ret;
    }
    
    public function fromArray($arr) {
        $vars = get_object_vars($this);
        foreach($vars as $nume => $valoare) {
            if(array_key_exists($nume, $arr)) {
                $this->$nume = $arr[$nume];
            } else {
                $this->$nume = '';
            }
        }
    }
}