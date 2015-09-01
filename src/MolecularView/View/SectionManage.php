<?php

namespace MolecularView\View;

class SectionManage
{

    private $sections = [];

    private $sectionStack = [];

    private $content = [];

    public function startSection($name){
        if(empty($name) || !is_string($name)) throw new \Exception("Section name is empty ou not string.",1);
        if(!empty($this->sectionStack)){
            if(empty($this->content[end($this->sectionStack)])) $this->content[end($this->sectionStack)] = '';
            $this->content[end($this->sectionStack)] .= ob_get_clean();
        }
        ob_start();
        array_push($this->sectionStack,$name);
    }

    public function stopSection(){
        if(empty($this->sectionStack)) throw new \Exception("Section stack is empty");
        $sectionName = array_pop($this->sectionStack);
        $content = ob_get_clean();
        debug($content);
        if(empty($this->content[$sectionName])) $this->content[$sectionName] = '';
        $this->sections[$sectionName] = $this->content[$sectionName].$content;
        unset($this->content[$sectionName]);
        $this->content[end($this->sectionStack)] .= $content;
    }

    public function getSection($name){
        if(empty($name) || !is_string($name) || !isset($this->sections[$name])) throw new \Exception("Section not found.");
        return $this->sections[$name];
    }
}