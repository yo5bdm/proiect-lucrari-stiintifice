<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller {
    private $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
    public function setData($content) {
        $this->view->setData($content);
    }
    
    public function __destruct() {
        $this->view->generate();
    }
}