<?php

include("../system/security.php");

class GoogleLogin {
	
	private $conn;
	private $security;
	private $email;
	private $password_hint;


	function __construct() {
		
		$this->security = new Security();
		
		$this->email = $this->security->post("email");
		$this->password_hint = $this->security->post("password_hint");
		
		$config = parse_ini_file("../config/db_conn.ini");
		$this->conn = mysqli_connect(
			$config["server"],
			$config["name"],
			$config["password"],
			$config["database"]);

		@mysqli_query($this->conn, "SET CHARACTER SET 'utf8'");
		
		$this->find_login();
	}
	
	
	private function find_login() {
		$sql = "SELECT * ";
		$sql.= "FROM google_login ";
		$sql.= "WHERE email = '" . $this->email . "' ";
		$sql.= "AND password_hint = '" . $this->password_hint . "'";
		$query = mysqli_query($this->conn, $sql);
		
		if (mysqli_num_rows($query) == 0) {
			echo "registration";
			exit;
		} else {
			$this->login();
		}
	}

}

new GoogleLogin();

?>