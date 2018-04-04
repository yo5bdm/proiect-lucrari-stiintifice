<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Profil extends Controller {
    
    public $permissions = array( //metodele protejate prin login;
        'edit','index'
    );
    
    public function view($id) { //cei neinregistrati pot vedea un anumit autor
        $this->setData(['id' => $id]);
    }
    
    
    protected function index() { //neinregistrati nu pot vedea lista de autori
        
    }
    
    protected function edit() { //neinregistrati nu pot edita lista de autori
        
    }
    
}