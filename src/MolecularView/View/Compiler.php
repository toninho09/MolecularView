<?php
namespace MolecularView\View;

class Compiler{
	private $tempFile;

	private $inlineFindStatementsRegex = '/\B@(\w+)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x';
	private $folder;
	
	private $compileMethods = null;
	public function __construct($folder = null){
		$this->compileMethods = new CompileMethods();
		$this->folder = !empty($folder)? $folder : sys_get_temp_dir();
		if(!file_exists($this->folder))
			throw new \Exception("folder $this->folder not exist");
	}

	public function getStatements($code){
		preg_match_all($this->inlineFindStatementsRegex, $code, $matches,PREG_SET_ORDER);
		return $matches;
	}	

	public function compile($file){
		if(!is_file($file))
			throw new \Exception("The file [$file] not exists.", 1);

		if($this->checkHasCompiledView($file)) return $this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file);
		$this->code = file_get_contents($file);
		$statements = $this->getStatements($this->code);
		$this->replaceStatements($statements);
    	file_put_contents($this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file), $this->code);
		return $this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file);
	}
	
	private function replaceStatements($statements){
		foreach($statements as $key => $value){
			$method = 'compile_'.$value[1];
			if(method_exists($this->compileMethods,$method)){
				$params = isset($value[3]) ? $this->cleanParams($value[3]) : null;
				$this->code = str_replace($value[0],$this->compileMethods->$method($params),$this->code);
			}
		}
	}
	
	private function cleanParams($params){
		$params = preg_replace('/^\(/','',$params);
		$params = preg_replace('/\)$/','',$params);
		return $params;
	}

	private function checkHasCompiledView($file){
		return file_exists($this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file));
	}

	private function getViewCacheName($file){
		return sha1($file.filemtime($file));
	}


}