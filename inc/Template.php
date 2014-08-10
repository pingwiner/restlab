<?php

class Template {
    private $mHtml;
    
    public function __construct($name) {
        $this->mHtml = file_get_contents('templates/' . $name . '.html');        
    }
    
    public function parse(array $values) {
        return str_replace(array_keys($values), array_values($values), $this->mHtml);
    }    
    
}
