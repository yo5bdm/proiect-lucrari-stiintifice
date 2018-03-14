<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Site extends Controller {
    
    public function index() {
        $useri = new Users();
        $data['users'] = $useri->getDataForTable();
        $this->setData($data);
    }
    
    public function login() {
        
    }
    
    public function logout() {
        User::logout();
        
    }
    
    public function register() {
        if(App::$app->isPost() != null) {
            $user = new User();
        }
    }
    
}