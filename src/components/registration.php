<?php

if (Security::post("registration")) {
	$email = Security::post("email");
	$password = Security::post("password");
	$repeat_password = Security::post("repeat_password");
	$team = Security::post("team");
	$about = Security::post("about");
	$city = Security::post("city");
	$street_name = Security::post("street_name");
	$zip_code = Security::post("zip");
	$category = Security::post("category");
	$league = Security::post("league");


	$fill = function () use ($email, $password, $repeat_password, $team, $about, $city, $street_name, $zip_code, $category) {
		$this->set("registration_email", $email);
		$this->set("registration_password", $password);
		$this->set("registration_repeat_password", $repeat_password);
		$this->set("registration_team", $team);
		$this->set("registration_about", $about);
		$this->set("registration_city", $city);
		$this->set("registration_street", $street_name);
		$this->set("registration_zip", $zip_code);
		$this->set("registration_category", $category);
	};


	if (empty($city)) {
		$this->set("registration_error", $this->get("empty_city"));
		$fill();
		return null;
	}

	if (empty($about)) {
		$this->set("registration_error", $this->get("empty_desc"));
		$fill();
		return null;
	}

	if (empty($street_name)) {
		$this->set("registration_error", $this->get("empty_street"));
		$fill();
		return null;
	}

	if (!preg_match("/^\d{5}$/", $zip_code) && $league == 1) {
		$this->set("registration_error", $this->get("invalid_zip"));
		$fill();
		return null;
	}

	if (empty($category)) {
		$this->set("registration_error", $this->get("empty_cat"));
		$fill();
		return null;
	}
	
	if (!filter_var(Security::post("email"), FILTER_VALIDATE_EMAIL)) {
		$this->set("registration_error", $this->get("invalid_mail"));
		$fill();
		return null;
	}

	//exist email
	if ($this->database->find_email(Security::post("email")) > 0) {
		$this->set("registration_error", $this->get("used_mail")); 
		$fill();
		return null;
	}

	//password max char
	if (strlen(Security::post("password")) < 6) {
		$this->set("registration_error", $this->get("short_pw")); 
		$fill();
		return null;
	}
	
	//password repeat
	if (Security::post("password") != Security::post("repeat_password")) {
		$this->set("registration_error", $this->get("diff_pw")); 
		$fill();
		return null;
	}

	//team max char
	if (strlen(Security::post("team")) < 5) {
		$this->set("registration_error", $this->get("short_team")); 
		$fill();
		return null;
	}

	//exist team
	if ($this->database->find_team(Security::post("team")) != 0) {
		$this->set("registration_error", $this->get("used_team")); 
		$fill();
		return null;
	}


	$this->database->registration_user(
		Security::post("email"),
		Security::post("password"),
		Security::post("team"),
		Security::post("about"),
		Security::post("league"),
		Security::post("city"),
		Security::post("street_name"),
		Security::post("zip"),
		Security::post("category")
	);
	
	$this->set("registration_error", "Uspešne si sa zaregistroval!");

	$this->set("registration_email", "");
	$this->set("registration_password", "");
	$this->set("registration_repeat_password", "");
	$this->set("registration_team", "");
	$this->set("registration_about", "");
	$this->set("registration_city", "");
	$this->set("registration_street", "");
	$this->set("registration_zip", "");
	$this->set("registration_category", "");

}

return null

?>
