<?php

class Parser extends Variables {

	private $match = "/@{(.*?)}/i";
	
	function __construct($file, $database) {
		parent::__construct();

		$this->database = $database;
		$this->content = $this->parse($file);
	}

	private function load_template($file) {
		if (file_exists("template/" . $file)) {
			return file_get_contents("template/" . $file);
		}
		$error = $this->get("page")["error-404"];
		return file_get_contents("template/" . $error);
	}

	
	private function parse($file) {
		return preg_replace_callback(
			$this->match,
			function($match) {
				return $this->replace_match($match[1]);
			},
			$this->load_template($file)
		);
	}
	
	
	public function replace_match($match) {
		$arguments = explode(":", $match);
		$key = array_shift($arguments);
		return $this->get($key, $arguments);
	}
	
	
	function __toString() {
		return $this->content;
	}
	
}

?>