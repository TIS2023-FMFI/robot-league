<?php

$year = $this->database->menu_archive_year();

$html = "";

while ($row = mysqli_fetch_assoc($year)) {
	$html.= "<li class=\"menu-item dropdown dropdown-submenu\">";
	$html.= "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">" . $row["year"] . "</a>";
	$html.= "<ul class=\"dropdown-menu\">";

	$num = 1;
	$assignment = $this->database->menu_archive_assignments($row["year"]);
	
	$html.= "<li class=\"menu-item\"><a href=\"?page=results&year=" . $row["year"] . "\">";
	$html.= $this->get("nav_assignments_results") . "</a></li>";
	
	while ($row0 = mysqli_fetch_assoc($assignment)) {
		$html.= "<li class=\"menu-item\"><a href=\"?page=assignment&id=" . $row0["id"] . "\">";
		$html.= $num . ". " . $this->get("nav_assignments_assignment") . "</a></li>";
		$num++;
	}
	$html.= "</ul>";
	$html.= "</li>";
}

return $html;

?>