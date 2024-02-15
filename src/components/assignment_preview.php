<?php
if (!$this->get("user", "admin") == 1){
    return "Permission denied!";
}

$lang = ["sk", "en", "de"][$_SESSION["lang"]];

$assignment_id = $_GET["id"];
$assignment = $this->database->assignment($assignment_id);

$html = "<h2>" . $this->get("assignment_task") . ": " . $assignment[$lang. "_title"] . "</h2>";
$html.= "<div>" . html_entity_decode($assignment[$lang . "_description"]) . "</div>";

return  $html;
?>

