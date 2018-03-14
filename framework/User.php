<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User {
    
    public static function login($id,$name,$username,$group,$admin) {
        App::$app->user->detalii($id,$name,$username,$group,$admin);
        App::$app->user->loggedIn = true;
        $_SESSION['user']['id'] = $id;
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['group'] = $group;
        $_SESSION['user']['admin'] = $admin;
        return true;
    }
    
    public static function get() {
        if(isset($_SESSION['user'])) {
            App::$app->user->detalii(
                    $_SESSION['user']['id'],
                    $_SESSION['user']['name'],
                    $_SESSION['user']['username'],
                    $_SESSION['user']['group'],
                    $_SESSION['user']['admin']
            );
            App::$app->user->loggedIn = true;
        } else {
            @User::logout();
        }
    }
    
    public static function logout() {
        App::$app->user->id = "";
        App::$app->user->name = "";
        App::$app->user->username = "";
        App::$app->user->group = "";
        App::$app->user->admin = "";
        App::$app->user->loggedIn = false;
        session_destroy();
    }
    
    private $name;
    private $username;
    private $admin;
    private $id;
    private $group;
    private $loggedIn=false;
    
    public function detalii($id,$name,$username,$group,$admin) {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->group = $group;
        $this->admin = $admin;
        $this->loggedIn = true;
    }
    
    public function isAdmin() {
        return $this->admin;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getId() {
        return $this->id;
    }

    public function getGroup() {
        return $this->group;
    }


}