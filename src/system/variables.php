<?php

class Variables extends Components {
	
	public $variables = [];
	
	function __construct() {
		$this->init_components();
		$this->init_functions();
		$this->init_translate();
	}
	
	
	public function get($name, $arguments = []) {
		if (array_key_exists($name, $this->variables)) {
			return $this->get_var($name, $this->to_array($arguments));
		}
		return NULL;
	}
	
	
	private function get_var($name, $arguments) {
		$lang = Language::get();
		if (is_callable($this->variables[$name][0])) {
			return call_user_func_array(
				$this->variables[$name][$lang], $arguments
			);
		}
		return $this->variables[$name][$lang];
	}
	
	
	function set($key, $sk, $en = NULL, $de = NULL) {
		$this->variables[$key][0] = $sk;
		if ($en) {
			$this->variables[$key][1] = $en;
		} else {
			$this->variables[$key][1] = $sk;
		}
                if ($de) {
                        $this->variables[$key][2] = $de;
                } else {
                        $this->variables[$key][2] = $sk;
                }
	}
	
	
	private function to_array($object) {
		if (is_array($object)) {
			return $object;
		}
		return [$object];
	}
	
}

?>
