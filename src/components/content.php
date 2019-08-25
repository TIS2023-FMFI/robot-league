<?php

$key = Security::get("page");

if (!$key) {
	$key = "home";
}

return $this->get("include_html", $this->get("page")[$key]);

?>