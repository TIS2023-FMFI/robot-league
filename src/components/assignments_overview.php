<?php

$remove_id = Security::get("remove-assignment");

if ($remove_id) {
	$this->database->remove_assignment($remove_id);
}

$today = date("d.m.Y 20:00");
$after = date("d.m.Y 20:00", strtotime("+2 week"));

if (
	Security::post("new-group") &&
	(bool)strtotime(Security::post("begin")) &&
	(bool)strtotime(Security::post("end"))
) {
	if (isset($_POST["selected_assignment"])) {
		$id_group = $this->database->create_assignemnt_group(
			date("Y-m-d H:i:s", strtotime(Security::post("begin"))),
			date("Y-m-d H:i:s", strtotime(Security::post("end")))
		);
	
		foreach ($_POST["selected_assignment"] as $key => $id) {
			$this->database->publish_assignemnts($id_group, $id);
		}
	}
}


$html = "<h2>" . $this->get("assignment_overview_list") . "</h2><form method='post'>";

if($this->get("user", "admin") == 1) {
	$html.= "<div>
			<a href='?page=new-assignment' class='btn btn-primary'><span class='glyphicon glyphicon-file'></span> " . $this->get("assignment_overview_new") . "</a>
			<input type='hidden' name='new-group' value='ok'>
			<button type='submit' class='btn btn-success disabled' id='new-assignment-group'><span class='glyphicon glyphicon-ok'></span> " . $this->get("assignment_overview_publish") . "</button>
	</div>
	<ul class='assignments-overview'>
		<li>
			<span>" . $this->get("assignment_overview_date_publish") . "</span>
			<input type='text' name='begin' value='".$today."' class='date_picker'>
		</li>
		<li>
			<span>" . $this->get("assignment_overview_date_dedline") . "</span>
			<input type='text' name='end' value='".$after."' class='date_picker'>
		</li>
	</ul>";
	$unpublished = $this->database->unpublished_assignment();
} else {
	$html.= "	<div><a href='?page=new-assignment' class='btn btn-primary'><span class='glyphicon glyphicon-file'></span> " . $this->get("assignment_overview_new") . "</a></div>";
	$unpublished = $this->database->unpublished_assignment_jury($this->get("user", "id"));
}


$html.= "<ul class='assignments-overview list'>";
while($row = mysqli_fetch_assoc($unpublished)) {
	$html.= "<li>";
	$html.= "<input type='checkbox' name='selected_assignment[]' value='" . $row["id"] . "'>";
	$html.= $row["sk_title"];
	$html.= "<a href='./?page=assignments-overview&remove-assignment=" . $row["id"] . "' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'></span> " . $this->get("assignment_overview_delete") . "</a>";
	$html.= "<a href='./?page=new-assignment&id=" . $row["id"] . "' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-wrench'></span> " . $this->get("assignment_overview_edit") . "</a>";
	$html.= "</li>";
}
$html.= "</ul>";
$html.= "</form>";


if ($this->get("user", "admin") == 1) {
	$html.= "<h2>" . $this->get("assignment_overview_published") . "</h2>";
	$published_group = $this->database->published_group();
	while($row_group = mysqli_fetch_assoc($published_group)) {
		$html.= "<div><strong>" . $this->get("assignment_overview_date_dedline") . ": " . $row_group["begin"] . " do: " . $row_group["end"] . "</strong>";
		$published_assignment = $this->database->published_assignment($row_group["id"]);
		$html.= "<ul class='assignments-overview list published'>";
		while($row_assignment = mysqli_fetch_assoc($published_assignment)) {
			$html.= "<li>" . $row_assignment["sk_title"];
			$html.= "<a href='./?page=new-assignment&id=" . $row_assignment["id"] . "' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-wrench'></span> " . $this->get("assignment_overview_edit") . "</a>";
			$html.= "</li>";
		}
		$html.= "</ul></div>";
	}
}


if ($this->get("user", "admin") == 1) {
	$html.= "<h2>" . $this->get("assignment_overview_past_the_deadline") . "</h2>";
	$after_deadline_group = $this->database->after_deadline_group();
	while($row_group = mysqli_fetch_assoc($after_deadline_group)) {
		$html.= "<div><strong>" . $this->get("assignment_overview_date_dedline") . ": " . $row_group["begin"] . " do: " . $row_group["end"] . "</strong>";
		$published_assignment = $this->database->published_assignment($row_group["id"]);
		$html.= "<ul class='assignments-overview list published'>";
		while($row_assignment = mysqli_fetch_assoc($published_assignment)) {
			$html.= "<li>" . $row_assignment["sk_title"];
			$html.= "<a href='./?page=new-assignment&id=" . $row_assignment["id"] . "' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-wrench'></span> " . $this->get("assignment_overview_edit") . "</a>";
			$html.= "</li>";
		}
		$html.= "</ul></div>";
	}
}


return $html;

?>