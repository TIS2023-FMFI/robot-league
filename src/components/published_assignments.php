<?php

$published_group = $this->database->published_group();

$html = "";

while($row_group = mysqli_fetch_assoc($published_group)) {
	$html.= "<div><strong>Zadanie od: " . $row_group["begin"] . " do: " . $row_group["end"] . "</strong>";
	$published_assignment = $this->database->published_assignment($row_group["id"]);
	$html.= "<ul class='assignments-overview list published'>";
	while($row_assignment = mysqli_fetch_assoc($published_assignment)) {
		$html.= "<li>" . $row_assignment["sk_title"] . "(" . $row_assignment["en_title"] . ", " . $row_assignment["de_title"] . ")";
		$html.= "<a href='./?page=new-assignment&id=" . $row_assignment["id"] . "' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-wrench'></span> Upraviť</a>";
		$html.= "</li>";
	}
	$html.= "</ul></div>";
}

return $html;

?>
