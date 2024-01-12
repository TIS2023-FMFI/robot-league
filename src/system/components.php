<?php

class Components extends Pages {
	
	function init_components() {
		$this->set("include_html", function($file) {
			return new Parser($file, $this->database);
		});

		
		$this->set("include_php", function($file) {
			return include("components/".$file.".php");
		}) ;


		$this->set("current_url", function($query = NULL) {
			$url = $this->get("path");

			parse_str($_SERVER["QUERY_STRING"], $exist);
			parse_str($query, $new);

			$marge_query = array_merge($exist, $new);
			$marge_query = array_filter($marge_query);

			if (!empty($marge_query)) {
				$url.= "?" . http_build_query($marge_query);
			}

			return $url;
		});


		$this->set("url_change_language", function() {
			$lang = ["en","de","sk"][Language::get()];
			return $this->get("current_url", "lang=" . $lang);
		});

		$this->set("url_change_language2", function() {
			$lang = ["de","sk","en"][Language::get()];
			return $this->get("current_url", "lang=" . $lang);
		});


		$this->set("path", function() {
			#$url = "//" . $_SERVER["HTTP_X_FORWARDED_SERVER"] . "/rl";
			$url = $_SERVER["HTTP_X_FORWARDED_SERVER"];
			$url.= str_replace("index.php", "", $_SERVER["PHP_SELF"]);
			return $url;
		});


		$this->set("user", function($key) {
			if (isset($_SESSION["user"])) {
				return $_SESSION["user"][$key];
			}

			return NULL;
		});
		
		
		$this->set("permission", function($admin = null, $jury = null, $user = null) {
			if ($user && !isset($_SESSION["user"])) {
				echo "permission denied";
				exit;
			}

			if ($admin && $this->get("user", "admin") != "1") {
				echo "permission denied";
				exit;
			} else
				
			if ($jury && ($this->get("user", "jury") != "1" && $this->get("user", "admin") != "1")) {
				echo "permission denied";
				exit;
			}
		});
		

		$this->set("token", function() {
			return md5(uniqid(rand(), true));
		});
		
		
		$this->set("team_name", function() {
			if ($this->get("user", "jury") != "1" && $this->get("user", "admin") != "1") {
				return $this->get("user", "name");
			} else {
				return $this->get("user", "mail");
			}
		});
	}
}

?>
