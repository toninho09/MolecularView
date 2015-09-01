<?php

namespace MolecularView\View;

/**
 * Class Composer
 * @package MolecularView\View
 */
class Composer
{
    private $viewFunctions =[];

    /**
     * set a new composer view
     * @param $name
     * @param callable $function
     */
    public function setViewComposer($name,callable $function){
        $this->viewFunctions[preg_quote(str_replace('/','\\',$name))] = $function;
    }

    /**
     * verific if has one composer for this view
     * @param $name
     * @return bool
     */
    public function hasComposer($name){
        $keys = array_keys($this->viewFunctions);
        $name = str_replace('/','\\',$name);
        foreach($keys as $key => $value){
            if(preg_match('/^'.$value.'$/',$name))return true;
        }
        return false;
    }

    /**
     * execute the composer view
     * @param View $view
     * @param $name
     */
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