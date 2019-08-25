<?php

$result = $this->database->menu_assignments();

$num = 1;
$html = "";

while ($row = mysqli_fetch_assoc($result)) {
	$html.= "<li><a href='?page=assignment&id=" . $row["id"] . "'>";
	$html.= $num . ". " . $this->get("nav_assignments_assignment") . "</a></li>";
	$num++;
}

if ($this->get("user", "admin") == 1 || $this->get("user", "jury") == 1) {
	$html.= "<li><a href='?page=assignments-overview'>" . $this->get("nav_assignments_overview") . "</a></li>";
}

return $html;

?>