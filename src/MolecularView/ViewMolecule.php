<?php
	namespace MolecularView;
	class ViewMolecule{

		protected $class="\MolecularView\View\View";
		
		protected static $instance;
		
	    public static function __callStatic($name, $arguments)
	    {
			$retorno = call_user_func_array(array(self::getInstance(),$name),$arguments);
			if(!is_null($retorno)) return $retorno;
			return self::getInstance();
		}
		
		public function __call($name, $arguments)
	    {
			$retorno = call_user_func_array(array(self::getInstance(),$name),$arguments);
			if(!is_null($retorno)) return $retorno;
			return self::getInstance();
		}
		
		public function register(\MolecularCore\Core &$app){
			if (!isset(self::$instance)) {
	            self::$instance = new $this->class($app);
				if(!empty($app->getConfig('view.folder')))
					self::$instance->setDefaultViewPath($app->getConfig('view.folder'));
	        }
		}
		
		public function run(){
		}
		
		public static function getInstance(){
			return self::$instance;
		}
	}