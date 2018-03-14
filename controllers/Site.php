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
        if(App::$app->isPost()) { 
            $userModel = new Users();
            $user = $userModel->verificare(App::$app->post['username'],App::$app->post['password']);
            if($user!=false) {
                User::login($user['id'], $user['name'], $user['username'], $user['group'], $user['admin']);
                $this->redirect(["c"=>"site","a"=>"index"]);
            }
            $this->alert("Datele introduse nu sunt corecte!");
        }
    }
    
    public function logout() {
        User::logout();
        $this->redirect(["c"=>"site","a"=>"index"]);
    }
    //{"id":0,"username":"admin","password":"admin","name":"Admin","group":""},
    public function register() {
        $userModel = new Users();
        if(App::$app->isPost()) {
            $user = array(
                'username'=>App::$app->post['username'],
                'password'=>App::$app->post['password'],
                'email'=>App::$app->post['email'],
                'name'=>App::$app->post['name'],
                'group'=>"",
                'admin'=>false
            );
            if($userModel->insertData($user)) {
                $this->alert("Utilizatorul a fost salvat, va rugam sa va logati");
                $this->redirect(["c"=>"site","a"=>"login"]);
            } else {
                $this->alert("Datele introduse nu sunt corecte!");
                //mergi inapoi la inregistrare
            }
        }
    }
    
}