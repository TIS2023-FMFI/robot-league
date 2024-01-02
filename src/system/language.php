<?php

class Language {
	const SK = 0;
	const EN = 1;
        const DE = 2;
	const STR = ["sk", "en", "de"];


	function __construct() {
		$this->initialize();
		Language::set();
	}


	private function initialize() {
		if (!isset($_SESSION["lang"])) {
			$_SESSION["lang"] = $this->language_detection();
		}
	}
	
	
	private static function language_detection() {
		$detect = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		if ($detect == "sk" || $detect == "cs") {
			return self::SK;
		}
                if ($detect == "de") {
                        return self::DE;
                }
		return self::EN;
	}


	static function get() {
		return $_SESSION["lang"];
	}


	static function set() {
		if (isset($_GET["lang"])) {
			if ($_GET["lang"] == "en") {
				$_SESSION["lang"] = self::EN;
			} else if ($_GET["lang"] == "de") {
                                $_SESSION["lang"] = self::DE;
                        } else {
				$_SESSION["lang"] = self::SK;
			}
		}
	}

}

new Language();

?>
