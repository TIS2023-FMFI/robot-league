<?php

$get_all_teams = $this->database->get_all_teams();

if (Security::get("delete")) {
	$this->database->remove_team(Security::get("delete"));
}



$html = "";

while($row = mysqli_fetch_assoc($get_all_teams)) {
	$html.= $row["name"] . " <a href='?page=teams&delete=" . $row["id_user"] . "'>Zmazať</a><br>";
}

return $html;

?>