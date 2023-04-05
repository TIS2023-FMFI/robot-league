<?php

class Security {
	
	static function get($key) {
		if (isset($_GET[$key])) {
			return stripslashes(htmlspecialchars($_GET[$key], ENT_QUOTES));
		}
		return NULL;
	}
	
	
	static function post($key) {
		if (isset($_POST[$key])) {
			return stripslashes(htmlspecialchars($_POST[$key], ENT_QUOTES));
		}
		return NULL;
	}
	
	static function whatever($something) {
		if (isset($something)) {
			return stripslashes(htmlspecialchars($something, ENT_QUOTES));
		}
		return NULL;
	}
	
	static function post_db($key) {
		if (isset($_POST[$key])) {
			return mysql_real_escape_string($_POST[$key]);
		}
		return NULL;
	}

	
	static function get_db($key) {
		if (isset($_GET[$key])) {
			return mysql_real_escape_string($_GET[$key]);
		}
		return NULL;
	}
	
}

?>
