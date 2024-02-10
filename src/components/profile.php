<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'system/database.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: ./");
    exit;
}

$userId = $_SESSION['user']['id'];
$userInfo = $this->database->getUserAndTeamInfo($userId);
$this->set("email", $userInfo['mail']);
$this->set("team_name", $userInfo['name']);
$this->set("city", $userInfo['city']);
$this->set("street_name", $userInfo['street_name']);
$this->set("zip", $userInfo['zip']);
$this->set("category", $userInfo['category']);
$this->set("about", $userInfo['description']);
$currentYear = date('Y');
$exists_solution = $this->database->check_for_this_years_solution($userId, $currentYear);

if (!$userInfo) {
    die('No user information found.');
}

if($exists_solution){
    $this->set("solution_exists" , True);
}
else{
    $this->set("solution_exists" , False);
}

//Profile edit
if (Security::post("profile_update")) {
    $email_update = Security::post("email_update");
    $team_update = Security::post("name_update");
    $about_update = Security::post("about_update");
    $city_update = Security::post("city_update");
    $street_name_update = Security::post("street_name_update");
    $zip_code_update = Security::post("zip_update");
    $category_update = Security::post("category_update");


    if (empty($city_update)) {
        $this->set("profile_error", "City name is required");
        return null;
    }

    if (empty($about_update)) {
        $this->set("profile_error", "Team description is required");
        return null;
    }

    if (empty($street_name_update)) {
        $this->set("profile_error", "Street name is required");
        return null;
    }

    if (!preg_match("/^\d{5}$/", $zip_code_update)) {
        $this->set("profile_error", "A valid 5-digit ZIP code is required");
        return null;
    }

    if (!filter_var(Security::post("email_update"), FILTER_VALIDATE_EMAIL)) {
        $this->set("profile_error", "Neplatný email");
        return null;
    }

    //exist email
    if ($this->database->find_email(Security::post("email_update")) > 0 and $email_update != $userInfo['mail']) {
        $this->set("profile_error", "Email už existuje!");
        return null;
    }

    //team max char
    if (strlen(Security::post("name_update")) < 5) {
        $this->set("profile_error", "Team musí mať aspoň 6 znakov!");
        return null;
    }

    //exist team
    if ($this->database->find_team(Security::post("name_update")) != 0 and $team_update != $userInfo['name']) {
        $this->set("profile_error", "Tento team už existuje!");
        return null;
    }


    $this->database->update_user(
        Security::post("email_update"),
        Security::post("name_update"),
        Security::post("about_update"),
        Security::post("city_update"),
        Security::post("street_name_update"),
        Security::post("zip_update"),
        Security::post("category_update"),
        $userId
    );

    $this->set("profile_error", "Uspešne si zmenil údaje v profile!");
    header("Location: ./");

    $this->set("email_update", "");
    $this->set("team_update", "");
    $this->set("about_update", "");
    $this->set("city_update", "");
    $this->set("street_name_update", "");
    $this->set("zip_update", "");
    $this->set("category_update", "");

}



return null;