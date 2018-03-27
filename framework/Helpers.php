<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpers {
    public static function table($data,$displayRows) {
        $ret = "<table class='table'><tr>";
        foreach($data['fields'] as $field) {
            if(in_array($field, $displayRows)) $ret .= "<th>".ucfirst ($field)."</th>";
        }
        $ret.="</tr>";
        foreach($data['date'] as $linie){
            $ret .= "<tr>";
            foreach($linie as $field => $c) {
                if(in_array($field, $displayRows)) $ret .= "<td>".$c."</td>";
            }
            $ret .= "</tr>";
        }
        $ret .= "</table>";
        return $ret;
    }
    
    public static function generateUrl($url) {
        switch(count($url)) {
            case 1: //avem controller
                return App::$app->settings->siteRoot().DS.$url['c'].DS.App::$app->settings->defaultRoute['action'];
            case 2: //controller si view
                return App::$app->settings->siteRoot().DS.$url['c'].DS.$url['a'];
            case 3: //controller, view si id
                return App::$app->settings->siteRoot().DS.$url['c'].DS.$url['a'].DS.$url['id'];
        }
        
        return App::$app->settings->siteRoot().DS.$url['c'].DS.$url['a'];
    }
    
    public static function a($url,$text) {
        if(is_array($url)) {
            return "<a href='" . Helpers::generateUrl($url) . "' >".$text."</a>";
        } else {
            return "<a href='" . $url . "' >".$text."</a>";
        }
        
    }
    
    public static function traducere($text) {
        if(array_key_exists($text, App::$app->settings->traducere)) {
            return App::$app->settings->traducere[$text];
        } else return ucfirst ($text);
    }
    
    public static function formular($class,$ngModel=null,$printArrays=true) {
        $reflect = new ReflectionClass($class);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $ret = "";
        foreach($props as $prop) {
            $pn = $prop->getName();
            if(is_array($class->$pn)) {
                if($printArrays == false) continue;
                $ret .= '<p><div class="input-group">
                        <input type="text" name="'. $prop->getName() .'"';
                if($ngModel) $ret .= ' ng-model="filtru.'.$prop->getName().'" ';
                $ret.=' class="form-control typeahead" placeholder="'. Helpers::traducere($prop->getName()).'">
                        <span class="input-group-btn">
                          <button class="btn btn-default" ng-click="add'. ucfirst($prop->getName()) .'()" type="button">Adauga</button>
                        </span>
                      </div></p>';               
            } elseif($class->getSettings($prop->getName())!=NULL && strcmp($class->getSettings($prop->getName()),"textarea")==0){
                
                $ret .= '<p><textarea placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control" rows="6" name="'. $prop->getName() .'" ng-model="'.$ngModel.".".$prop->getName().'" ></textarea></p>';
                
            } elseif($class->getSettings($prop->getName())!=NULL && strcmp($class->getSettings($prop->getName()),"password")==0){
                $ret .='<p><input type="password" name="'. $prop->getName() .'"'; 
                if($ngModel) $ret .= ' ng-model="'.$ngModel.".".$prop->getName().'" ';
                $ret.=' placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control"/></p>';
                
            } else {
                $ret .='<p><input type="text" name="'. $prop->getName() .'"'; 
                if($ngModel) $ret .= ' ng-model="'.$ngModel.".".$prop->getName().'" ';
                $ret.=' placeholder="'. Helpers::traducere($prop->getName()).'" class="form-control"/></p>';
            }
        }
        return $ret;
    }
    
    
}