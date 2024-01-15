<?php

$id = Security::get("id");
$best = Security::get("best");
$lang = ["sk", "en", "de"][$_SESSION["lang"]];
$today = Time();

$assignment_group = $this->database->assignment_group($id);
if (strtotime($assignment_group["begin"]) < time())
{
  $deadline = date($this->get("assignment_time_format"), strtotime($assignment_group["end"]));
  
  $html = "<h3>" . $this->get("assignment_deadline") . ": " . $deadline . "</h3>";
  error_log("xx: " . $this->get("assignment_deadline") . ": " . $deadline);
  
  $num = 1;
  
  $assignments = $this->database->assignments($id);
  while ($row = mysqli_fetch_assoc($assignments)) {
  	$html.= "<h2>" . $num . ". " . $this->get("assignment_task") . ": " . $row[$lang . "_title"] . "</h2>";
  	$html.= "<div>" . html_entity_decode($row[$lang . "_description"]) . "</div>";
  	if (
  		isset($_SESSION["user"]) &&
  		strtotime($assignment_group["end"]) > $today &&
  		strtotime($assignment_group["begin"]) < $today &&
  		$this->get("user", "jury") == 0 &&
  		$this->get("user", "admin") == 0
  	) {
		$team = $this->database->getUserAndTeamInfo($_SESSION["user"]["id"]);
		$html.= "<p class='alert alert-info'>" . $this->get("category_info") . $this->get($team["category"]) . ".</p>";
  		$html.= "<a href='?page=new-solution&id-assignment=" . $row["id"] . "'>" . $this->get("assignment_solutions_update") . " " . $num . "</a>";
  	}
  	$num++;
  }
}

if ($best && $this->get("user", "admin") == 1) {
	$teams = $this->database->best_solution($assignment_group["id"], $best);
	$is_best = mysqli_fetch_assoc($teams);

	$teams = $this->database->best_solution($assignment_group["id"], $best);
	
	while($teams_row = mysqli_fetch_assoc($teams)) {
		$is_best = (($teams_row["best"] == 1) ? 0: 1);
		$this->database->change_best_solution($teams_row["id"], $is_best);
	}
	
	header("Location: " . $this->get("current_url", "best=&group="));
}


//Show solutions
if (strtotime($assignment_group["end"]) < $today || $this->get("user", "admin") == 1) {
	$html.= "<h2>" . $this->get("assignment_solutions") . "</h2>";
	$html.= "<ul id=\"solutions\">";

	$solution = $this->database->assignment_solutions($id);
	
	while ($row = mysqli_fetch_assoc($solution)) {
		if ($row["team"]!="") {
			$html.= "<li class=\"" . $row["best"] . "\">";

			$html.= "<div class='commented'>";
				if ($this->database->assignment_solutions_comment($_SESSION["user"]["id"], $row["id"]) == 1) {
					$html.= " <img src='image/tick.png' alt='commented'>";
				}
			$html.= "</div>";
			
			$html.= "<a href=\"?page=solution&id-assignment=" . $id . "&id-team=" . $row["id_team"] . "\">" . $row["team"] . "</a>";
			if ($this->get("user", "admin") == 1) {
				$html.= " <a href='?page=assignment&id=" . $id . "&best=" . $row["id_team"] . "' class='best_alert'>Best</a>";
			}
			$html.= "</li>";
		}
	}

	$html.= "</ul>";
}

return $html;

?>
