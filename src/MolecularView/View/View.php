<?php
namespace MolecularView\View;

class View{
	private $file;
	private $defaultViewPath;
	private $data;
	private $compiler;

	function __toString(){
		return $this->render();
	}

	function __construct(){
		$this->file = '';
		$this->defaultViewPath = '';
		$this->viewRender = '';
		$this->data = [];
		$this->compiler = new Compiler();
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
			throw new Exception("Data is invalid.", 1);
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
	
	public function render($file = '' , $data = []){
		if(empty($file)){
			$file = $this->file;
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
}