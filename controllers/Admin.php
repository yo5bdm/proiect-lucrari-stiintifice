<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin extends Controller {
    
    public $admin = true;
    
    public $permissions = array( //metodele protejate prin login;
        'index'
    );
    
    protected function index() {
        
    }
    
}