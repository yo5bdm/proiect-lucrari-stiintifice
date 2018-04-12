<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends Model {
    private $error='';
    
    public function insertData($date) {
        $date['id'] = $this->getID();
        $date['admin'] = FALSE;
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
    public function updateUser($user) {
        $fields = array('nume','prenume','password','email','functia','group');
        for($i=0;$i < count($this->data['Users']);$i++){
            if($this->data['Users'][$i]['id'] == App::$app->user->getId()) {
                foreach ($fields as $field) {
                    $this->data['Users'][$i][$field] = $user['datele'][$field];
                }
                return true;
            }
        }
        return false;
    }
    public function validateUser($user) {
        $req = array('nume','prenume','username','password','email','functia');
        foreach($req as $field) { //if any is empty
            if(strlen($user->$field)==0) { 
                $this->error = "Nu ati completat toate datele obligatorii!";
                return false; 
            }
        }
        foreach($this->data['Users'] as $dbuser) { //check if any exists
            if(strcasecmp($user->nume, $dbuser['nume'])==0 && 
               strcasecmp($user->prenume, $dbuser['prenume'])==0) { 
                    $this->error = "Combinatia de nume si prenume exista deja in baza de date!";
                    return false; 
               }
            if(strcasecmp($user->username, $dbuser['username'])==0){ 
                    $this->error = "Numele de utilizator exista deja! Va rugam sa alegeti alt nume de utilizator";
                    return false; 
               }
            if(strcasecmp($user->email, $dbuser['email'])==0){ 
                    $this->error = "E-mailul a mai fost folosit! Va rugam sa folositi alt email. Daca sunteti sigur ca aceasta este adresa Dvs de e-mail, va rugam sa incercati recuperarea contului.";
                    return false; 
               }
        }
        return true; //everything ok
    }
    public function getError() {
        return $this->error;
    }
} 