<?php
$lang = ["sk", "en", "de"][$_SESSION["lang"]];
$id_team = Security::get("id-team");

if (!($this->get("user", "admin") == 1 || ($this->get("user", "id")!=null && $this->get("user", "id") == $id_team))){
    return "Permission denied!";
}

$userInfo = $this->database->getUserAndTeamInfo($id_team);

$id_assignment = Security::get("id-assignment");
$assignment = $this->database->assignment($id_assignment);

$id_group = $assignment["id_group"];
$get_solution = $this->database->solution_content($id_group, $id_team);

$solution = mysqli_fetch_assoc($get_solution);

$get_photos     = $this->database->get_solution_photos($solution["id_solution"]);
$get_video      = $this->database->get_solution_video($solution["id_solution"]);
$get_program    = $this->database->get_solution_program($solution["id_solution"]);



$html = "<h3>" . $this->get("solution_team_name") . ": " . $userInfo["name"] . "</h3>";
$html.= "<h3>" . $this->get("solution_team_info") . ": " . $userInfo["description"] . "</h3>";
$html.= (($userInfo["sk_league"] == 1)? "": "Open league");


$html.= "<h2><a id='task' href=?page=assignment&id=" . $id_group . ">" . $this->get("solution_assignment") . ": " . $assignment[$lang . "_title"] . "</a></h2>";


$html.= "<div>" . html_entity_decode($solution["text"]) . "</div>";


$html.= "<h2>" . $this->get("solution_photos") . ":</h2>";
$html.= "<div id='photo_gallery'>";

while($solution_photo = mysqli_fetch_assoc($get_photos)) {
	$html.= "<a data-fancybox=\"gallery\" href='components/get_image.php?id=" . $solution_photo["token"] . "&.jpg' rel='group_" . $solution["id_solution"] . "'>";
	$html.="<img src='components/get_image.php?id=" . $solution_photo["token"] . "&min=1'>";
	$html.= "</a>";
}
$html.= "</div>";
$html.= "<script src=\"https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js\"></script>
<script>
    Fancybox.bind('[data-fancybox=\"gallery\"]', {
    //
    });    
</script>";


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
return $html
?>