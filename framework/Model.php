<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Model {
    
    private $data;
    private $class;
    
    public function __construct() {
        $this->class = get_class($this);
        $this->data = $this->loadFile($this->class);
    }

    private function loadFile($file) {
        if(file_exists('db'.DS.$file.".json")) {
            return json_decode(file_get_contents('db'.DS.$file.".json"),true);
        }
    }
    
    public function getCount() {
        return count($this->data[$this->class]);
    }
    
    public function getData() {
        return $this->data[$this->class];
    }
    
    public abstract function insertData($data);
    
    public function saveData() {
        file_put_contents('db'.DS.$file.".json", $data);
    }
    
    public function getFields() {
        $fields = array();
        if($this->getCount() > 0) {
            foreach($this->data[$this->class][0] as $field => $data) {
                array_push($fields, $field);
            }
        }
        return $fields;
    }
    
    public function getDataForTable() {
        $data['fields'] = $this->getFields();
        $data['date'] = $this->getData();
        return $data;
    }
    
}