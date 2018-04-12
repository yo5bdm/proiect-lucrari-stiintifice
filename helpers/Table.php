<?php

/* 
 * Copyright Erdei Rudolf (www.erdeirudolf.com) - All rights reserved.
 * Code available under the GPL V2 license terms and conditions
 */

class Table extends Html {
    private $data; //the complete data to be displayed in the table
    private $displayColumns; //array of columns to be displayed
    private $html='';
    private $rowData='';
    
    public function setData($data) {        
        $this->data = $data;
        return $this;
    }
    
    public function setDisplayColumns($rows) {
        $this->displayColumns = $rows;
        return $this;
    }
    
    public function generate() {
        $this->initTable();
        $this->appendHeader();
        $this->appendRow();
        foreach($$this->data['date'] as $linie){
            $this->generateLine($linie);
            $this->appendRow();
        }
        $this->closeTable();
        return $this->html;
    }
    
    private function initTable() {
        $this->html = "<table class='table'>";
    }
    private function closeTable(){
        $this->html .= "</table>";
    }
    
    private function appendHeader() {
        foreach($this->data['fields'] as $field) {
            if(in_array($field, $this->displayColumns)) $this->rowData .= "<th>".ucfirst ($field)."</th>";
        }
    }
    
    private function generateLine($linie) {
        foreach($linie as $field => $c) {
            if(in_array($field, $displayRows)) $this->rowData .= "<td>".$c."</td>";
        }
    }
    private function appendRow() {
        $this->html .='<tr>';
        $this->html .= $this->rowData;
        $this->html .= '</tr>';
        $this->rowData ='';
    }
}