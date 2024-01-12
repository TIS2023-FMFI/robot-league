<?php

$id_group = Security::get("id-assignment");
$id_team = Security::get("id-team");
$lang = ["sk", "en", "de"][$_SESSION["lang"]];

$expired_assignment = $this->database->expired_assignment($id_group);

if ($expired_assignment == 1 && $this->get("user", "admin") != 1) {
	echo "permission denied";
	exit;
}


if (Security::post("create_comment") == "ok") {
	foreach ($_POST["comment"] AS $key => $value) {
		$rating = $_POST["rating"][$key];
		$category = $_POST["category"];
		if ($rating > 3) $rating = 3;
		if ($rating < 0) $rating = 0;

		$get_jury_comment = $this->database->get_comment($key, $this->get("user", "id"));
		
		echo $rating;
		
		if (mysqli_num_rows($get_jury_comment) == 0) {
			$this->database->set_comment(
				$key,
				$this->get("user", "id"),
				str_replace("\n", "<br>", Security::whatever($_POST["comment"][$key])),
				$rating,
				$category
			);
		} else {
			$this->database->update_comment(
				$key,
				$this->get("user", "id"),
				str_replace("\n", "<br>", Security::whatever($_POST["comment"][$key])),
				$rating,
				$category
			);
		}
	}
	
	//header("Location: ?page=solution&id-assignment=" . $id_group . "&id-team=" . $id_team);
}



$get_solution = $this->database->solution_content($id_group, $id_team);
$user = mysqli_fetch_assoc($get_solution);

$html = "<h3>" . $this->get("solution_team_name") . ": " . $user["team"] . "</h3>";
$html.= (($user["sk_league"] == 1)? "": "Open league");
$html.= "<form method='post'><input type='hidden' name='create_comment' value='ok'>";


$get_solution = $this->database->solution_content($id_group, $id_team);
while($row = mysqli_fetch_assoc($get_solution)) {
	$get_photos		= $this->database->get_solution_photos($row["id_solution"]);
	$get_video			= $this->database->get_solution_video($row["id_solution"]);
	$get_program		= $this->database->get_solution_program($row["id_solution"]);
	$show_coment	= $this->database->show_coment($id_group);


	$html.= "<h2><a id='task' href=?page=assignment&id=" . $id_group . ">" . $this->get("solution_assignment") . ": " . $row[$lang . "_title"] . "</a></h2>";
	$html.= "<p>" . html_entity_decode ($row["text"]) . "</p>";

	$html.= "<h2>" . $this->get("solution_photos") . ":</h2>";
	$html.= "<ul id='photo_gallery'>";

	while($solution_photo = mysqli_fetch_assoc($get_photos)) {
		$html.= "<li><a href='components/get_image.php?id=" . $solution_photo["token"] . "&.jpg' rel='group_" . $row["id_solution"] . "'>";
		$html.="<img src='components/get_image.php?id=" . $solution_photo["token"] . "&min=1'>";
		$html.= "</a></li>";
	}
	$html.= "</ul>";

	$html.= "<h2>" . $this->get("solution_videos") . ":</h2>";
	while($solution_video = mysqli_fetch_assoc($get_video)) {
		$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
		$replace    = "youtube.com/embed/$1";
		$url = preg_replace($search, $replace, $solution_video["link"]);
		$html.= "<p><iframe width='560' height='315' src='https://www.youtube.com/embed/" . $url . "?rel=0' frameborder='0' allowfullscreen></iframe></p>";
	}

	$html.= "<h2>" . $this->get("solution_programs") . ":</h2>";
	$html.= "<ul>";
	while($solution_program = mysqli_fetch_assoc($get_program)) {
		$html.= "<li><a href='components/download_attachment.php?id=" . $solution_program["token"] . "'>";
		$html.= $solution_program["original_name"];
		$html.= "</a></li>";
	}
	$html.= "</ul>";

	
	// all people
	if ($this->get("user", "admin") == 0 && $this->get("user", "jury") == 0) {
		$get_coment		= $this->database->get_coment($row["id_solution"]);
		if ($show_coment == 0) {
			$html.= "<h2>" . $this->get("solution_rating") . ":</h2>";
			$html.= "<p class='pre_commnets'>" . html_entity_decode($get_coment["text"]) . "</p>";
		}
	}
	
	//for jury
	if ($this->get("user", "admin") == 0 && $this->get("user", "jury") == 1) {
		if ($show_coment != 0) {
			$get_jury_comment = mysqli_fetch_assoc($this->database->get_comment($row["id_solution"], $this->get("user", "id")));

			$html.= "<h2>" . $this->get("solution_rating") . ":</h2>";
			$html.= "<ul id='rating'><li><textarea name='comment[".$row["id_solution"]."]'>" . str_replace("<br>", "\n", $get_jury_comment["text"]) . "</textarea></li>";
			$html.= "<li>Body: <input type='number' step='0.1' min='0' max='3' name='rating[".$row["id_solution"]."]' value='" . $get_jury_comment["points"] . "'></li></ul>";
		} else {
			$get_coment = $this->database->get_coment($row["id_solution"]);
			$html.= "<h2>" . $this->get("solution_rating") . ":</h2>";
			$html.= "<p class='pre_commnets'>" . html_entity_decode($get_coment["text"]) . "</p>";			
		}
		$html.="<input type='hidden' name='category' value='0'>";
	}
	
	//for admin
	if ($this->get("user", "admin") == 1) {
		$get_jury_comments = $this->database->get_jury_comments($row["id_solution"]);
		$get_admin_comment = $this->database->get_comment($row["id_solution"], $this->get("user", "id"));
		$comments = "";
		$index = 0;
		$ratio = 0;
		
		$html.= "<h2>" . $this->get("solution_rating") . ":</h2>";
				
		while($comment_row = mysqli_fetch_assoc($get_jury_comments)) {
			$html.= "<div class='comment_block'>" . $comment_row["mail"] . "<br>" . $comment_row["text"] . "<br><strong>Body: " . $comment_row["points"] . "</strong></div>";
			$comments.= ++$index . ". " . $comment_row["text"] . "<br><br>";
			$ratio+= $comment_row["points"];
		}
		
		$num_comments = mysqli_num_rows($get_jury_comments);
		if ($num_comments == 0) $num_comments = 1;
		@$ratio/= $num_comments;
		
		if (mysqli_num_rows($get_admin_comment) > 0) {
			$get_admin_comment_row = mysqli_fetch_assoc($get_admin_comment);
			$comments = $get_admin_comment_row["text"];
			$ratio = $get_admin_comment_row["points"];
		}
		
		$html.= "<ul id='rating'><li><textarea name='comment[".$row["id_solution"]."]'>" . str_replace("<br>", "\n", $comments) . "</textarea></li>";
		$html.= "<li>Body: <input type='number' step='0.001' min='0' max='3' name='rating[".$row["id_solution"]."]' value='" . $ratio . "'>";

		if (mysqli_num_rows($get_admin_comment) > 0) {
			$admin_commented = true;
		}
		
		$html.= "</li>";
		$html.= "<li>Kategória: <select name='category'><option value='1'>Zajace</option><option value='2'>Tigre</option></select></li>";
		$html.= "</ul>";
	}
}



if ($this->get("user", "admin") == 1 || $this->get("user", "jury") == 1) {
	
	$html.= "<div id='save_comment'><input type='submit' class='btn btn-primary' value='" . $this->get("save_rating") . "'>";
	if (@$admin_commented) {
		$html.= "<span class='rating_was_saved'>" . $this->get("rating_was_saved") . "</span>";
	}
	$html.= "</div>";
	
	$solutions = $this->database->back_solution($id_group);
	$back = null;
	$next = null;
	$i = 0;
	$actual = 0;
	$solution = Array();
	while($row = mysqli_fetch_array($solutions)) {
		$solution[$i] = $row;
		if($row["id_team"] == $id_team) {
			$actual = $i;
		}
		$i++;
	}

	$html.= "<p id='pager'>";
	if ($actual != 0) {
		$html.= "<a href='?page=solution&id-assignment=" . $id_group . "&id-team=" . $solution[$actual-1]["id_team"] . "' class='btn btn-success'>" . $this->get("solution_back") . "</a>";
	}
	if ($actual != count($solution)-1) {
		$html.= "<a href='?page=solution&id-assignment=" . $id_group . "&id-team=" . $solution[$actual+1]["id_team"] . "' class='btn btn-success'>" . $this->get("solution_next") . "</a>";
	}
	
	$html.= "</p>";
}

$html.= "</form>";

return $html;

?>
