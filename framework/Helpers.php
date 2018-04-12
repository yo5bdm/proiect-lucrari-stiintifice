<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpers {
    
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

}