<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lucrari extends Controller {
    
    public $permissions = array( //metodele protejate prin login; trebuie sa fie protected
        'add', 'edit', 'delete', 'index'
    );
    
    protected function index() {
        //https://mdbootstrap.com/javascript/charts/
    }
    
    protected function add() {
        
    }
    
    public function view($id) {
        $this->setData(['id'=>$id]);
    }
    
    protected function edit($id) {
        $this->setData(['id'=>$id]);
    }
    
}