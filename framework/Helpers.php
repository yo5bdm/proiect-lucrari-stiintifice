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
        return App::$app->settings->url.DS.App::$app->settings->index."?c=".$url['c']."&a=".$url['a'];
    }
    
}