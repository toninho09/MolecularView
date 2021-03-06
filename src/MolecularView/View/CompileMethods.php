<?php
namespace MolecularView\View;

class CompileMethods{

    private $customCompile =[];
    /**
     * compile @if($expression)
     * @param $expresion
     * @return string
     */
	public function compile_if($expresion){
		return "<?php if($expresion): ?>";
	}

    /**
     * compile @else
     * @param $expression
     * @return string
     */
	public function compile_else($expression){
		return "<?php else: ?>";
	}

    /**
     * compile @elseif($expression)
     * @param $expression
     * @return string
     */
	public function compile_elseif($expression){
		return "<?php elseif($expression): ?>";
	}

    /**
     * compile @endif
     * @param $expression
     * @return string
     */
	public function compile_endif($expression){
		return "<?php endif; ?>";
	}

    /**
     * compile @while($expression)
     * @param $expression
     * @return string
     */
	public function compile_while($expression){
		return "<?php while($expression): ?>";
	}

    /**
     * compile @endwhile
     * @param $expression
     * @return string
     */
	public function compile_endwhile($expression){
		return "<?php endwhile; ?>";
	}

    /**
     * compile @for($expression)
     * @param $expression
     * @return string
     */
	public function compile_for($expression){
		return "<?php for($expression): ?>";
	}

    /**
     * compile @endfor
     * @param $expression
     * @return string
     */
	public function compile_endfor($expression){
		return "<?php endfor; ?>";
	}

    /**
     * compile @brack
     * @param $expression
     * @return string
     */
    public function compile_break($expression){
        return "<?php break; ?>";
    }

    /**
     * compile @foreach($expression)
     * @param $expression
     * @return string
     */
    public function compile_foreach($expression){
        return "<?php foreach($expression): ?>";
    }

    /**
     * compile @endforeach
     * @param $expression
     * @return string
     */
    public function compile_endforeach($expression){
        return "<?php endforeach; ?>";
    }

    /**
     * compile @continue
     * @param $expression
     * @return string
     */
    public function compile_continue($expression){
        return "<?php continue; ?>";
    }

    /**
     * compile @switch($expression)
     * @param $expression
     * @return string
     */
    public function compile_switch($expression){
        return "<?php switch($expression): ?>";
    }

    /**
     * compile @endswitch
     * @param $expression
     * @return string
     */
    public function compile_endswitch($expression){
        return "<?php endswitch; ?>";
    }

    /**
     * compile @case($expression)
     * @param $expression
     * @return string
     */
    public function compile_case($expression){
        return "<?php case $expression : ?>";
    }

    /**
     * compile @default
     * @param $expression
     * @return string
     */
    public function compile_default($expression){
        return "<?php default : ?>";
    }

    public function compile_section($expression){
        return '<?php $this->sectionManage->startSection('.$expression.'); ?>';
    }

    public function compile_endSection($expression){
        return '<?php $this->sectionManage->stopSection(); ?>';
    }

    public function compile_yield($expression){
        return '<?php echo $this->sectionManage->getSection('.$expression.'); ?>';
    }

    public function compile_include($expression){
        return '<?php $this->render('.$expression.'); ?>';
    }

    /**
     * create or redefine a new compile function
     * @param string $name
     * @param callable $callback
     */
    public function extend($name ,callable $callback){
        $this->customCompile[$name] = $callback;
    }

    /**
     * return if has a extend compile
     * @param $name
     * @return bool
     */
    public function hasExtendCompile($name)
    {
        return isset($this->customCompile[$name]) && is_callable($this->customCompile[$name]);
    }

    /**
     * execute te extend function
     * @param $name
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function executeExtendCompile($name,$param){
        if($this->hasExtendCompile($name)){
            return $this->customCompile[$name]($param);
        }
        throw new \Exception("function not extists",1);
    }
}