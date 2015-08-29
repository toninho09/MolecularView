<?php
namespace MolecularView\View;

class CompileMethods{
	
	public function compile_if($expresion){
		var_export($expresion);
		return "<?php if($expresion) ?>";
	}
	
}