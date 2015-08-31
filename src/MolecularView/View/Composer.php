<?php

namespace MolecularView\View;

class Composer
{
    private $viewFunctions =[];

    public function setViewComposer($name,callable $function){
        $this->viewFunctions[preg_quote(str_replace('/','\\',$name))] = $function;
    }

    public function hasComposer($name){
        $keys = array_keys($this->viewFunctions);
        $name = str_replace('/','\\',$name);
        foreach($keys as $key => $value){
            if(preg_match('/^'.$value.'$/',$name))return true;
        }
        return false;
    }

    public function executeComposer(View &$view,$name){
        $name = str_replace('/','\\',$name);
        if($this->hasComposer($name) ){
            $keys = array_keys($this->viewFunctions);
            foreach($keys as $key => $value){
                if(preg_match('/'.$value.'/',$name)){
                    $this->viewFunctions[preg_quote($name)]($view);
                }
            }
        }
    }

}