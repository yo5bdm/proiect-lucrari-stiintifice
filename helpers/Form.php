<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Form extends Helpers {
    private $class;
    private $props;
    private $ngModel=null;
    private $printArrays=true;
    private $allrequired=false;
    private $excludedFields=array();
    private $printPostValues=false;
    private $post;
    
    private $html='';
    
    
    public function __construct($class) {
        $this->class = $class;
        $reflect = new ReflectionClass($class);
        $this->props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        return $this;
    }
    
    //setters
    public function setNgModel($ngModel) {
        $this->ngModel = $ngModel;
        return $this;
    }
    public function printArrays() {
        $this->printArrays = true;
        return $this;
    }
    public function dontPrintArrays() {
        $this->printArrays = false;
        return $this;
    }
    public function setAllRequired($true) {
        $this->allrequired = $true;
        return $this;
    }
    public function excludeField($field) {
        array_push($this->excludedFields,$field);
        return $this;
    }
    public function printPostValues($postData) {
        $this->printPostValues = true;
        $this->post = $postData;
        return $this;
    }
    public function dontPrintPostValues() {
        $this->printPostValues = false;
        $this->post = null;
        return $this;
    }
    
    
    public function generate() {
        $this->html = '';
        foreach($this->props as $prop) {
            if($this->isExcluded($prop)) { continue; }
            if($this->isArray($prop)) {
                $this->appendArray($prop);          
            } elseif($this->isTextArea($prop)){                
                $this->appendTextArea($prop);
            } elseif($this->isPassword($prop)){
                $this->appendPasswordField($prop);
            } else {
                $this->appendInputField($prop);
            }
        }
        return $this->html;
    }
    
    //private
    private function isArray($prop) {
        $pn = $prop->getName();
        if(is_array($this->class->$pn) && $this->printArrays) { return true; } 
        else { return false; }
    }
    private function isTextArea($prop) {
        if($this->class->getSettings($prop->getName())!=NULL && strcmp($this->class->getSettings($prop->getName()),"textarea")==0) { return true; }
        else { return false; }
    }
    private function isPassword($prop) {
        if($this->class->getSettings($prop->getName())!=NULL && strcmp($this->class->getSettings($prop->getName()),"password")==0) { return true; }
        else { return false; }
    }
    private function isExcluded($prop) {
        if(in_array($prop->getName(), $this->excludedFields)) 
                { return true; }
        else { return false; }
    }
    
    private function appendArray($prop) {
        $this->html .= '<p><div class="input-group">
                <input type="text" name="'. $prop->getName() .'"';
        $this->appendOptions($prop);
        $this->html.=' class="form-control typeahead" placeholder="'. Helpers::traducere($prop->getName()).'"/>
                <span class="input-group-btn">
                  <button class="btn btn-default" ng-click="add'. ucfirst($prop->getName()) .'()" type="button">Adauga</button>
                </span>
              </div></p>';     
    }
    private function appendTextArea($prop) {
        $this->html .= '<p><textarea placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control" rows="6" name="'. $prop->getName() .'" ';
        $this->appendOptions($prop);
        $this->html .='>';
        $this->appendPostValue($prop);
        $this->html .= '</textarea></p>';
    }
    private function appendPasswordField($prop) {
        $this->html .='<p><input type="password" name="'. $prop->getName() .'"'; 
        $this->appendOptions($prop);
        $this->html.=' placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control" value="';
        $this->appendPostValue($prop);
        $this->html.='" /></p>';
    }
    private function appendInputField($prop) {
        $this->html .='<p><input type="text" name="'. $prop->getName() .'"'; 
        $this->appendOptions($prop);
        $this->html .=' placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control" value="';
        $this->appendPostValue($prop);
        $this->html.='" /></p>';
    }
    
    //options
    private function appendPostValue($prop) {
        if($this->printPostValues && $this->post) { 
            $pn = $prop->getName();
            $this->html .= $this->post->$pn;
        }
    }
    
    private function appendOptions($prop) {
        if($this->ngModel) { $this->appendNgModel($prop); }
        if($this->allrequired) { $this->appendRequired(); }
    }
    private function appendNgModel($prop) {
        $this->html .= ' ng-model="'.$this->ngModel.".".$prop->getName().'" ';
    }
    private function appendRequired() {
        $this->html .= ' required="required" ';
    }
}