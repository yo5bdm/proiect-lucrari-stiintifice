<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Route {
    public $controller=null;
    public $action=null;
    public $id=null;
    public $parametrii=null;
    
    public function __construct() {
        $comanda = $this->proceseaza(); 
        $nr = count($comanda);
        //print_r($comanda);
        switch ($nr) {
            case 0:
                $this->controller = App::$app->settings->defaultRoute['controller'];
                $this->action = App::$app->settings->defaultRoute['action'];
                break;
            case 1:
                $this->controller = $comanda[0];
                $this->action = App::$app->settings->defaultRoute['action'];
                break;
            case 2:
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                break;
            case 3:
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                break;
            case 4:
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                $this->parametrii = $comanda[3];
                break;
            default:
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                for($i=3;$i<$nr;$i++) {
                    $this->parametrii[key($comanda[$i])] = $comanda[$i];
                }
        }
    }
    
    private function proceseaza() {
        $vars = explode('/',$_SERVER['REQUEST_URI']);
        $ret = array();
        if(App::$app->settings->appFolder != null) $nr = 2;
        else $nr = 1;
        for($i=$nr;$i<count($vars);$i++) {
            array_push($ret, $vars[$i]);
        }
        return $ret;
    }
    
}