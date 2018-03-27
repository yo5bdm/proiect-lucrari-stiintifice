<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends Model {
    public function insertData($date) {
        $date['id'] = $this->getID();
        array_push($this->data['Users'], $date);
        return true;
    }
    
    protected function getID() {
        $id = 0;
        foreach($this->data['Users'] as $user) {
            if($user['id']>$id) {
                $id = $user['id'];
            }
        }
        return $id+1;
    }
    
    public function verificare($username,$password) {
        foreach($this->data['Users'] as $user) {
            if($user['username']==$username && $user['password']==$password) {
                $n = new User();
                $arr = array();
                $n->setId($user['id']);
                $n->nume = $user['nume'];
                $n->prenume = $user['prenume'];
                $n->username = $user['username'];
                $n->email = $user['email'];
                $n->functia = $user['functia'];
                $n->group = $user['group'];
                $n->setAdmin($user['admin']);
                return $n;
            }
        }
        return false;
    }
    
    public function getById($id) {
        foreach($this->data['Users'] as $user) {
            if($user['id']==$id) {
                return $user;
            }
        }
        return false;
    }
    
    public function getByName($name) {
        $u = array();
        $lowerName = strtolower($name);
        foreach($this->data['Users'] as $user) {
            if(strpos(strtolower($user['nume']),$lowerName)!=false || 
               strpos(strtolower($user['prenume']),$lowerName)!=false) {
                $usr = new User();
                array_push($u, $usr->fromArray($user));
            }
        }
        if(count($u)==0) return false;
        else return $u;
    }
    
    public function getNameList() {
        $u = array();
        foreach($this->data['Users'] as $user) {
            $usr = new User();
            array_push($u, $usr->fromArray($user)->asArray());
        }
        if(count($u)==0) return false;
        else return $u;
    }
}