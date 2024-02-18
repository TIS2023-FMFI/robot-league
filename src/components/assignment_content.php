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
		if (empty($team["category"])) {
			$html.= "<p class='alert alert-info'>" . $this->get("category_empty") . "</p>";
		} else {
			$html.= "<p class='alert alert-info'>" . $this->get("category_info") . $this->get($team["category"]) . ".</p>";
		}
  		$html.= "<a href='?page=new-solution&id-assignment=" . $row["id"] . "&id-team=" . $_SESSION["user"]["id"] . "'>" . $this->get("assignment_solutions_update") . " " . $num . "</a>";
  	}
  	$num++;
  }
}
$assignment_number = 1;
$category = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
	foreach ($_POST["category"] AS $key => $value) {
		if (isset($_POST['submit'][$key])) {
		$assignment_number = $_POST["assignment_number"][$key];
		$category = $_POST["category"][$key];
		$this->database->set_category($key, $category);
		$this->database->set_best_assignment_number($key, $assignment_number);
		}	
		if (isset($_POST['unsubmit'][$key])) {		
		$this->database->unset_best($key);
		}
			
	}    
}



//Show solutions
if (strtotime($assignment_group["end"]) < $today || $this->get("user", "admin") == 1 || $this->get("user", "jury") == 1) {
	$html.= "<form method='post' action=''>";
	$html.= "<h2>" . $this->get("assignment_solutions") . "</h2>";
	$html.= "<ul id=\"solutions\">";

	$solution = $this->database->assignment_solutions($id);
	$count = $this->database->count_assignments($id);
    $c = mysqli_fetch_row($count);
    $count = $c[0];
	
	
	while ($row = mysqli_fetch_assoc($solution)) {
		if ($row["team"]!="") {
			
			$teamm = $this->database->get_team_category($row["id_team"]);
			$t = mysqli_fetch_row($teamm);
			$teamm_cat = $t[0];
			
			$cat = 1;
			if ($teamm_cat == "Tigre"){
				$cat = 2;
			}
			
			$html .= "<li class=\"" . $row["best"] . "" . ($row["best"] == "best" ? $row["best_assignment_number"] : "") . "" . ($row["best"] == "best" ? "_" . $cat : "") . "\">";

			$html .= "<a href=\"?page=solution&id-assignment=" . $id . "&id-team=" . $row["id_team"] . "\" class='space1'>" . $row["team"] . "</a>";
			if($this->get("user", "admin") == 1 || $this->get("user", "jury") == 1){
				if ($this->database->assignment_solutions_comment($_SESSION["user"]["id"], $row["id"]) == 1) {
					$html .= " <img src='image/tick.png' alt='commented'>";
				}
			}
			
			
			if ($this->get("user", "admin") == 1) {
				$html .= " Kategória: <select name='category[".$row["id_team"]."]'>";
				$html .= "<option value='1'" . ($cat == 1 ? " selected" : "") . ">Zajace</option>";
				$html .= "<option value='2'" . ($cat == 2 ? " selected" : "") . ">Tigre</option>";
				$html .= "</select>";
				$html .= " Úloha č.: <input type='number' step='1' min='1' max='". $count ."' name='assignment_number[".$row["id_team"]."]' value='" . ($row["best_assignment_number"] == 0 ? "1" : $row["best_assignment_number"]) . "'>";
					if ($row["best"] == 'best'){
						$html .= "<input type='submit' name='unsubmit[".$row["id_team"]."]' value='Remove Best'>";
					}																														
					else{
							$html .= "<input type='submit' name='submit[".$row["id_team"]."]' value='Best'>";
					}
			}
			
			if ($row["id_team"]==$this->get("user", "id")){
				$adminButton = ">";
				if ($this->get("user", "admin") == 1){
					$adminButton = " style='float: right;' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-file'></span> " . $this->get("assignment_solutions_update");
				}
				$html.= "<a href='?page=new-solution&id-assignment=" . $row["id_assignment"] . "&id-team=" . $row["id_team"] . "' ". $adminButton . "</a>";
			}else{
				$adminButton = ">";
				if ($this->get("user", "admin") == 1){
					$adminButton = " style='float: right;' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-wrench'></span> " . $this->get("admin_solution_update");
				}
				$html.= "<a href='?page=new-solution&id-assignment=" . $row["id_assignment"] . "&id-team=" . $row["id_team"] . "' " . $adminButton . "</a>";
			}
			$html.= "</li>";
		}
	}

	$html.= "</ul>";
	$html .= "</form>";
}

return $html;

?>
