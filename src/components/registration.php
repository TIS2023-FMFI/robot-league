<?php

if (Security::post("registration")) {
	//valid email
	$this->set("registration_email", Security::post("email"));
	$this->set("registration_password", Security::post("password"));
	$this->set("registration_repeat_password", Security::post("repeat_password"));
	$this->set("registration_team", Security::post("team"));
	$this->set("registration_about", Security::post("about"));
	
	
	
	
	if (!filter_var(Security::post("email"), FILTER_VALIDATE_EMAIL)) {
		$this->set("registration_error", "Neplatný email");
		
		return null;
	}

	//exist email
	if ($this->database->find_email(Security::post("email")) > 0) {
		$this->set("registration_error", "Email už existuje!"); 
		return null;
	}

	//password max char
	if (strlen(Security::post("password")) < 6) {
		$this->set("registration_error", "Heslo musí mať aspoň 6 znakov!"); 
		return null;
	}
	
	//password repeat
	if (Security::post("password") != Security::post("repeat_password")) {
		$this->set("registration_error", "Heslá sa nezhodujú"); 
		return null;
	}

	//team max char
	if (strlen(Security::post("team")) < 5) {
		$this->set("registration_error", "Tím musí mať aspoň 5 znakov"); 
		return null;
	}

	//exist team
	if ($this->database->find_team(Security::post("team")) != 0) {
		$this->set("registration_error", "Tento team už existuje!"); 
		return null;
	}
	
	
	$this->database->registration_user(
		Security::post("email"),
		Security::post("password"),
		Security::post("team"),
		Security::post("about"),
		Security::post("league")
	);
	
	$this->set("registration_error", "Uspešne si sa zaregistroval!");

	$this->set("registration_email", "");
	$this->set("registration_password", "");
	$this->set("registration_repeat_password", "");
	$this->set("registration_team", "");
	$this->set("registration_about", "");
}

return null

?>
