<?php
namespace MolecularView\View;

class Compiler{
	private $tempFile;

	private $inlineFindStatementsRegex = '/\B@(\w+)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x';
	private $folder;
	
	public $compileMethods = null;

	public function __construct($folder = null){
		$this->compileMethods = new CompileMethods();
		$this->folder = !empty($folder)? $folder : sys_get_temp_dir();
		if(!file_exists($this->folder))
			throw new \Exception("folder $this->folder not exist");
	}

	/**
	 * get all the view comands
	 * @param $code
	 * @return mixed
	 */
	public function getStatements($code){
		preg_match_all($this->inlineFindStatementsRegex, $code, $matches,PREG_SET_ORDER);
		return $matches;
	}

	/**
	 * compile the view and returns the file name
	 * @param $file
	 * @return string
	 * @throws \Exception
	 */
	public function compile($file){
		if(!is_file($file))
			throw new \Exception("The file '$file'' not exists.", 1);
		if($this->checkHasCompiledView($file)) return $this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file);
		$this->code = file_get_contents($file);
		$statements = $this->getStatements($this->code);
		$this->replaceStatements($statements);
    	file_put_contents($this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file), $this->code);
		return $this->folder. DIRECTORY_SEPARATOR . $this->getViewCacheName($file);
	}

	/**
	 * do
	 * @param $statements
	 * @throws \Exception
	 */
	private function replaceStatements($statements){
		foreach($statements as $key => $value){
			$method = 'compile_'.$value[1];
			$params = isset($value[3]) ? $this->cleanParams($value[3]) : null;
			//cuida dos metodos default de compilação
			if(method_exists($this->compileMethods,$method)){
				$this->code = str_replace($value[0],$this->compileMethods->$method($params),$this->code);
				//cuida dos metodos extendidos
			}elseif($this->compileMethods->hasExtendCompile($value[1])) {
				$this->code = str_replace($value[0], $this->compileMethods->executeExtendCompile($value[1], $params), $this->code);
				//cuida dos metodos de extends
			}elseif($value[1] == 'extends'){
				$this->code = str_replace($value[0],'', $this->code);
				$this->code .= '<?php $this->render('.$params.')?>';
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