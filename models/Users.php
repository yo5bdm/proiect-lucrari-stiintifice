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
                return $user;
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
}