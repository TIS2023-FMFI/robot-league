<?php

if (Security::get("delete")) {
	$this->database->remove_jury(Security::get("delete"));
}

require("phpmailer/class.phpmailer.php");

require("components/util.php");


$my_mail = Security::post("mail");

$exist_email = $this->database->find_email($my_mail);

if ($exist_email > 0) {
	$this->set("send_email", "E-mail už existuje");
}

if ($my_mail && filter_var($my_mail, FILTER_VALIDATE_EMAIL) && $exist_email == 0) {
	
	$password = generateRandomString(6);
	$this->database->create_jury($my_mail, $password);
	
	$content = "Bol vytvorený nový rozhodcovský účet na stránke https://liga.robotika.sk/<br><br>";
	$content.= "Prihlasovací email: " . $my_mail . "<br>";
	$content.= "Prihlasovacie heslo: " . $password . "<br>";
	
	$mail = new PHPMailer;
	$mail->isSendmail();
	$mail->setFrom("no-reply@mail.com", "Roboticka Liga");

	$mail->addAddress($my_mail, "Rozhodca");
	$mail->Subject = "Robotická liga";
	$mail->msgHTML($content);

	if (!$mail->send()) {
		$this->set("send_email", "E-mail nebol odoslaný");
	} else {
		$this->set("send_email", "E-mail bol odoslaný");
	}
}

$get_all_jury = $this->database->get_all_jury();
$get_all_admins = $this->database->get_all_admins();

$html = "<h2>Admin</h2>";

while($row = mysqli_fetch_assoc($get_all_admins)) {
	$html.= $row["mail"] . "<br>";
}

$html.= "<h2>Rozhodcovia</h2>";
while($row = mysqli_fetch_assoc($get_all_jury)) {
	$html.= $row["mail"] . " <a href='?page=jury&delete=" . $row["id"] . "' class='remove_jury'>Zmazať</a><br>";
}


return $html;

?>
