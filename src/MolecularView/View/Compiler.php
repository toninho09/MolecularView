<?php
namespace MolecularView\View;

class Compiler{
	private $tempFile;

	public __construct(){

	}

	public function replaceInFile(){

	}

	public function compile($file){
		if(!is_file($file)){
			throw new Exception("The file [$file] not exists.", 1);
		}
		$this->tempfile = file_get_contents($file);

	}
}