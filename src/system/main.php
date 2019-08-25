<?php

session_start();

class Main {

	private $files = [
		"config/translate.php",
		"system/language.php",
		"system/database.php",
		"system/security.php",
		"system/pages.php",
		"system/components.php",
		"system/variables.php",
		"system/parser.php"
	];

	
	function __construct() {
		foreach ($this->files as $file) {
			error_log("including " . $file);
			@include_once($file);
		}
		
		echo new Parser("main.htm", new Database());
	}
}

?>
