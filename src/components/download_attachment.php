<?php

$config = parse_ini_file("../config/db_conn.ini");
$conn = mysqli_connect(
	$config["server"],
	$config["name"],
	$config["password"],
	$config["database"]);

@mysqli_query($conn, "SET CHARACTER SET 'utf8'");

$sql = "SELECT * FROM programs WHERE token = '" . $_GET["id"] . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$file = "../attachments/solutions/" . $row["id_solution"] . "/programs/" . $row["id"] . "." . $row["extension"];

if (file_exists($file)) {

    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for internet explorer
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($file));
    header("Content-Disposition: attachment; filename=" . str_replace(" ", "_", $row["original_name"]));
    readfile($file);
    die();
} else {
    die("Error: File not found.");
}
?>