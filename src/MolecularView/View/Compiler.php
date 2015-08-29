<?php
namespace MolecularView\View;

class Compiler{
	private $tempFile;

	private $inlineFindStatementsRegex = "/\B@(\w+)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x";
	
	private $compileMethods = null;
	public function __construct(){
		$this->compileMethods = new CompileMethods();  
	}

	public function getStatements($code){
		preg_match_all($this->inlineFindStatementsRegex, $code, $matches,PREG_SET_ORDER);
		return $matches;
	}	

	public function compile($file){
		if(!is_file($file))
			throw new \Exception("The file [$file] not exists.", 1);
		$this->code = file_get_contents($file);
		$statements = $this->getStatements($this->code);
		$this->replaceStatements($statements);
			
    	file_put_contents(sys_get_temp_dir(). DIRECTORY_SEPARATOR . sha1($file), $this->code);
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
}