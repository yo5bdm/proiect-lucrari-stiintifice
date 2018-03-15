<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lucrari extends Controller {
    
    public $permissions = array( //metodele protejate prin login;
        'add', 'edit', 'delete'
    );
    
    public function index() {
        
    }
    
    public function add() {
        if(App::$app->isPost()) { 
            $p = App::$app->post;
            $lucrare = new Lucrare($p['titlu'], $p['abstract'], $p['volum'], $p['pagini'], $p['conferinta'], $p['anulPublicarii'], $p['link'], $p['linkLocal']);
            $modelDate = new Date();
            $modelDate->salveazaLucrarea($lucrare);
            $this->alert("Lucrarea a fost salvata");
            $this->redirect(['c'=>'lucrari','a'=>'index']);
        }
    }
    
    public function view($id) {
        $modelDate = new Date();
        //$this->setData();
    }
    
    public function edit($id) {
        if(App::$app->isPost()) { }
    }
    
    public function delete($id) {
        if(App::$app->isPost()) { 
            
        }
        else $this->redirect (["c"=>"lucrari","a"=>"index"]);
    }
}