<?php

class User extends Obiect {
    
    public $nume;     
    public $prenume;
    public $username;
    public $password;
    public $email;
    public $functia;
    protected $admin;
    protected $id;
    public $group;
    
    protected $settings = array(
        'password' => 'password'
    );
    
    public function __construct() {
       $this->group = array();
    }
    
    
    public function isAdmin() {
        return $this->admin;
    }
    
    public function setID($id) {
        $this->id = $id;
    }
    public function setAdmin($admin) {
        $this->admin = $admin;
    }

    public function getId() {
        return $this->id;
    }
    
    public function fromPost() {
        $ar = array('nume', 'prenume', 'username', 'password', 'email', 'functia');
        foreach($ar as $a) {
            $this->$a = App::$app->post[$a];
        }
    }
    
    public function fromArray($arr) {
        $ar = array('id','nume', 'prenume', 'username', 'email', 'functia');
        foreach($ar as $a) {
            $this->$a = $arr[$a];
        }
        return $this;
    }
    
    public function asArray($save=false) {
        
        $arr = array();
        if($save) $ar = array('id','nume', 'prenume','password', 'username', 'email', 'functia');
        else $ar = array('id','nume', 'prenume', 'username', 'email', 'functia');
        foreach($ar as $a) {
            $arr[$a]=$this->$a;
        }
        return $arr;
    }
 
}