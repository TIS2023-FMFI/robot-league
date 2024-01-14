<?php

$lang = ["sk", "en", "de"][$_SESSION["lang"]];

$html = "<h2>" . $this->get("assignment_task") . ": " . $_GET[$lang. "_title"] . "</h2>";
$html.= "<div>" . html_entity_decode($_GET[$lang . "_assignment"]) . "</div>";

return  $html;
?>

