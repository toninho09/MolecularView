<?php
namespace MolecularView\View;

class View{
	private $file;
	private $defaultViewPath;
	private $data;
	private $compiler;
	private $composer;
	private $sectionManage;

	function __toString(){
		return $this->render();
	}

	function __construct(){
		$this->file = '';
		$this->defaultViewPath = '';
		$this->viewRender = '';
		$this->data = [];
		$this->compiler = new Compiler();
		$this->composer = new Composer();
		$this->sectionManage = new SectionManage();
	}

	public function setDefaultViewPath($path){
		$this->defaultViewPath = $path;
	}

	public function setFile($file){
		$this->file = $file;
	}

	public function getDefaultViewPath(){
		return $this->defaultViewPath;
	}

	public function with($name, $value = ''){
		if(empty($name)){
			throw new Exception("Var name is not valid.", 1);
		}
		if(is_array($name)){
			$this->data = array_merge($this->data,$name);
		}else{
			$this->data = array_merge($this->data,[$name=>$value]);
		}
	}

	public function compile($file){
		return $this->compiler->compile($file);
	}

	public function extendCompiler($name,callable $callback){
		$this->compiler->compileMethods->extend($name,$callback);
	}
	
	public function render($file = '' , $data = []){
		if(empty($file)){
			$file = $this->file;
		}
		if($this->composer->hasComposer($file)){
			$this->composer->executeComposer($this,$file);
		}
		extract($this->data);
		extract($data);
		ob_start();
		$fileFormat = $this->defaultViewPath.$file;
        $compiledView = $this->compile($fileFormat);
		if (file_exists($compiledView)){
			include $compiledView;
		}else{
			throw new \Exception("File [$fileFormat] Not Found.");
		}
		return ob_get_clean();
	}

	public function composer($name,callable $function){
		$this->composer->setViewComposer($name,$function);
	}
}