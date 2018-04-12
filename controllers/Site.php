<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Site extends Controller {
    
    public $permissions = array( //metodele protejate prin login;
        'logout', 'profil'
    );
    
    protected function index() {
        //pagina principala a aplicatiei
    }
    
    protected function login() {
        if(App::$app->isPost()) { 
            $userModel = new Users();
            $user = $userModel->verificare(App::$app->post['username'],App::$app->post['password']);
            if($user!=false) {
                Loggeduser::login($user);
                $this->redirect(["c"=>"site","a"=>"index"]);
            }
            $this->alert("Datele introduse nu sunt corecte!");
        }
    }
    
    protected function logout() {
        Loggeduser::logout();
        $this->redirect(["c"=>"site","a"=>"index"]);
    }
    //{"id":0,"username":"admin","password":"admin","name":"Admin","group":""},
    protected function register() {
        $userModel = new Users();
        if(App::$app->isPost()) {
            $user = new User();
            $user->fromPost();
            if(!$userModel->validateUser($user)) {
                $this->alert($userModel->getError());
                $this->setData($user);
            } elseif($userModel->insertData($user->asArray(true))) {
                $this->alert("Utilizatorul a fost salvat, va rugam sa va logati");
                $this->redirect(["c"=>"site","a"=>"login"]);
            } else {
                $this->alert("Datele introduse nu sunt corecte!");
                $this->setData($user);
                //mergi inapoi la inregistrare
            }
        }
    }
    
    protected function profil() {
        
    }
}