<?php
require("phpmailer/class.phpmailer.php");

require("components/util.php");

if ((Security::post("reset") == Security::post("rstpsswd")) && (Security::post("reset") != ""))
{
  $random_psswd = generateRandomString(6);
  error_log("reset password (" . $random_psswd . ") for " . Security::post("mail"));
  $email_address_to_reset = Security::post("mail");
  $reset_result = $this->database->reset_password($email_address_to_reset, $random_psswd); 
  
  if ($reset_result == 1)
     $reset_msg_result = "Password has been changed.";
  else
     $reset_msg_result = "An account with e-mail '" . $email_address_to_reset . "' has not been found."; 

  error_log("result of reset passwd: " . $reset_result . " = " . $reset_msg_result); 

  if ($reset_result == 1)
  {
      $content = "Niekto poziadal o zmenu hesla pre Vas ucet na stranke liga.robotika.sk.<br>";
      $content .= "Heslo bolo zmenene na nasledujuci nahodny retazec: '" . $random_psswd . "'<br><br>";
      $content .= "Somebody requested to change the password on your account at liga.robotika.sk.<br>";
      $content .= "Your password has been changed to a random one: '" . $random_psswd . "'<br><br>";
      $content .= "Jemand hat darum gebeten, das Passwort für Ihr Konto bei liga.robotika.sk zu ändern.<br>";
      $content .= "Das Passwort wurde in folgende zufällige Zeichenfolge geändert: '" . $random_psswd . "'<br><br>";
      
      $mail = new PHPMailer;
      $mail->isSendmail();
      $mail->setFrom("pavel.petrovic@gmail.com", "Roboticka Liga");
    
      $mail->addAddress($email_address_to_reset, "Sutaziaci");
      $mail->Subject = "Roboticka liga";
      $mail->msgHTML($content);

      if (!$mail->send()) {
          $this->set("send_email", "E-mail nebol odoslaný");
          $reset_msg_result .= "User has NOT been notified by e-mail (could not send).";
          error_log("reset: '" . $email_address_to_reset . "' could not be notified.");
      } else {
          $reset_msg_result .= "User has been notified by e-mail.";
          error_log("reset: '" . $email_address_to_reset . "' has been notified.");
      }
  }
  echo $reset_msg_result;
}

if (Security::post("login")) {
	$_SESSION["user"] = $this->database->login(
		Security::post("mail"),
		Security::post("password")
	);
	$this->database->last_logged($_SESSION["user"]["id"]);
	header("Location: ./");
	
// Po prihlaseni ma zostane na povodnej stranke
//	header("Location: " . $this->get("current_url"));
}

if(Security::get("logout") == "ok") {
	unset($_SESSION["user"]);
	header("Location: ./");
}

if(isset($_SESSION["user"])) {
	return $this->get("include_html", "navigator/account-logout.htm");
}

return $this->get("include_html", "navigator/account-login.htm");

?>
