<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lucrari extends Controller {
    
    public $permissions = array( //metodele protejate prin login;
        'add', 'edit', 'delete', 'index'
    );
    
    protected function index() {
        //https://mdbootstrap.com/javascript/charts/
    }
    
    protected function add() {
        
    }
    
    protected function view($id) {
        $modelDate = new Date();
        //$this->setData();
    }
    
    protected function edit($id) {
        if(App::$app->isPost()) { }
    }
    
    protected function delete($id) {
        if(App::$app->isPost()) { 
            
        }
        else $this->redirect (["c"=>"lucrari","a"=>"index"]);
    }
}