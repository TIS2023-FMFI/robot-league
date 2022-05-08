<?php

$config = parse_ini_file("../config/db_conn.ini");
$conn = mysqli_connect(
	$config["server"],
	$config["name"],
	$config["password"],
	$config["database"]);

@mysqli_query($conn, "SET CHARACTER SET 'utf8'");

$sql = "SELECT * FROM images_solution WHERE token = '" . $_GET["id"] . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


if (isset($_GET["min"])) {
	$image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".jpg";
        if (!file_exists($image_path)) {
	  $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".JPG";
          if (!file_exists($image_path)) {
	    $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".png";
            if (!file_exists($image_path)) {
	      $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".PNG";
              if (!file_exists($image_path)) {
	        $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".glb";
                if (!file_exists($image_path)) {
	          $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/small/" . $row["id"] . ".GLB";
                }
              }
            }
          }
        }
} else {
	$image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".jpg";
	if (!file_exists($image_path)) {
	  $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".JPG";
	  if (!file_exists($image_path)) {
	    $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".png";
	    if (!file_exists($image_path)) {
		$image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".PNG";
	        if (!file_exists($image_path)) {
	   	    $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".glb";
	            if (!file_exists($image_path)) {
	   	        $image_path = "../attachments/solutions/" . $row["id_solution"] . "/images/big/" . $row["id"] . ".GLB";
                    }
                }
            }
          }
	}
}

switch($row["extension"]) {
    case "gif": $type="image/gif"; break;
    case "PNG":
    case "png": $type="image/png"; break;
    case "jpeg":
    case "JPG":
    case "jpg": $type="image/jpeg"; break;
    case "glb":
    case "GLB": $type="model/gltf-binary"; break;
    default:
}

header("Content-Type: " . $type);
readfile($image_path);
die();

?>
