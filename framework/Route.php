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
        switch ($nr) {
            case 0: //nimic, le luam cele default
                $this->controller = App::$app->settings->defaultRoute['controller'];
                $this->action = App::$app->settings->defaultRoute['action'];
                break;
            case 1: //avem doar controller
                $this->controller = $comanda[0];
                $this->action = App::$app->settings->defaultRoute['action'];
                break;
            case 2: //avem controller si action
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                break;
            case 3: //avem controller, action si id
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                break;
            case 4: //controller, action, id si alti parametrii
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                $this->parametrii = $comanda[3];
                break;
            default: //case 4 cu mai multi parametrii
                $this->controller = $comanda[0];
                $this->action = $comanda[1];
                $this->id = $comanda[2];
                for($i=3;$i<$nr;$i++) {
                    $this->parametrii[key($comanda[$i])] = $comanda[$i];
                }
        }
        $this->controller = ucfirst($this->controller);
    }
    
    private function proceseaza() {
        /* scoatem folderul in care e aplicatia din request si intoarcem 
         * restul elementelor ca array(controller, action, id, etc)
         */
        $ret = array();
        $uri = str_replace(App::$app->settings->appFolder,'',$_SERVER['REQUEST_URI']);
        $ret = explode('/',$uri);
        if(count($ret)==1 && strlen($ret[0])==0) {
            $ret = array();
        }
        return $ret;
    }
    
}