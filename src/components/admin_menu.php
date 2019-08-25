<?php
$html = "";
if ($this->get("user", "admin") == 1) {
	$html = "					<li>
						<a href='?page=archive' class='dropdown-toggle' data-toggle='dropdown'>" . $this->get("nav_users") . "<b class='caret'></b></a>
						<ul class='dropdown-menu'>
							<li><a href='?page=teams'>" . $this->get("nav_teams") . "</a></li>
							<li><a href='?page=jury'>" . $this->get("nav_jury") . "</a></li>
						</ul>
					</li>";
}

return $html;

?>
