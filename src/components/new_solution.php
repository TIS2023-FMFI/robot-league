<?php

include("resize_image.php");
include("youtube_parser.php");

$id_assignment = Security::get("id-assignment");

//check for filled in profile
$userId = $_SESSION['user']['id'];

if (isset($_GET["id-team"])){
	$userId = $_GET["id-team"];
}

$userInfo = $this->database->getUserAndTeamInfo($userId);
$assignment = $this->database->assignment($id_assignment);
if (empty($userInfo["category"])) {
	$this->set("cat_info", "<p class='alert alert-info'>" . $this->get("category_empty") . "</p>");
} else {
	$this->set("cat_info", "<p class='alert alert-info'>" . $this->get("category_info") . $this->get($userInfo["category"]) . ".</p>");
}
if(is_null($userInfo['city']) or is_null($userInfo['street_name']) or is_null($userInfo['zip']) or
	is_null($userInfo['category']) or is_null($userInfo['description'])){
	$this->set("UpdateProfile", True);
}

//editable
$expired_assignment = $this->database->expired_assignment($assignment["id_group"]);
if ($expired_assignment == 1 && $this->get("user", "admin") != "1") {
	echo "permission denied";
	exit;
}

function is_image($img){
	return (bool)getimagesize($img);
}


$this->set("assignment_title", $assignment["sk_title"]);
$this->set("assignment_group_id", $assignment["id_group"]);

$solution = $this->database->exist_solution($id_assignment, $userId);
$this->set("solution_text", $solution["text"]);


$id_solution = $solution["id"];

if (Security::post("save_solution")) {
	if ($solution["id"]) {
		$this->database->update_solution(
			$id_assignment,
			$userId,
			Security::post("solution")
		);
	} else {
		$id_solution = $this->database->create_solution(
			$id_assignment,
			$userId,
			Security::post("solution")
		);
	}
	

	$this->set("solution_text", Security::post("solution"));

	// save youtube link
	$youtube = new Youtube(Security::post("youtube_link"));
	//$url_youtube = Security::post("youtube_link");

	if ($youtube->valid()) {
		$this->database->add_youtube_link(0, $id_solution, $youtube->get_id());
	}

	// upload image
	@mkdir("attachments/solutions/" . $id_solution, 0755);
	@mkdir("attachments/solutions/" . $id_solution . "/images", 0755);
	@mkdir("attachments/solutions/" . $id_solution . "/images/big", 0755);
	@mkdir("attachments/solutions/" . $id_solution . "/images/small", 0755);
	
	$total = count($_FILES["upload_images"]["name"]);

	for($i=0; $i<$total; $i++) {
		$tmpFilePath = $_FILES["upload_images"]["tmp_name"][$i];
		

		if ($tmpFilePath != "" && is_image($tmpFilePath)){
			$extension = strtolower(pathinfo($_FILES["upload_images"]["name"][$i], PATHINFO_EXTENSION));
			$id_image = $this->database->solution_image($id_solution, $extension, $this->get("token"));

			$newFilePathBig = "attachments/solutions/" . $id_solution . "/images/big/" . $id_image . "." . $extension;
			$newFilePathSmall = "attachments/solutions/" . $id_solution . "/images/small/" . $id_image . ".jpg";
			
			resize_fit_width($tmpFilePath, $newFilePathSmall);
			move_uploaded_file($tmpFilePath, $newFilePathBig);
		}
	}
	
	
	// upload program and files
	@mkdir("attachments/solutions/" . $id_solution, 0755);
	@mkdir("attachments/solutions/" . $id_solution . "/programs", 0755);
	
	$total = count($_FILES["upload_programs"]["name"]);

	for($i=0; $i<$total; $i++) {
		$tmpFilePath = $_FILES["upload_programs"]["tmp_name"][$i];
		
		if ($tmpFilePath != "" && $_FILES["fileToUpload"]["size"] <= (pow(1024, 2)*10)){

			$extension = strtolower(pathinfo($_FILES["upload_programs"]["name"][$i], PATHINFO_EXTENSION));
			$id_program = $this->database->solution_program($id_solution, $extension, $_FILES["upload_programs"]["name"][$i], $this->get("token"));

			$newFilePath = "attachments/solutions/" . $id_solution . "/programs/" . $id_program . "." . $extension;
			
			move_uploaded_file($tmpFilePath, $newFilePath);
		}
	}

	$this->set("save_ok", "<div class='alert alert-success fade in alert-dismissable' id='save_alert'><strong>" . $this->get("solution_save_result") . "</strong></div>");
}


// get youtube videos
$get_all_youtube_link = $this->database->get_all_youtube_link(0, $id_solution);
$youtube_link = "";
while ($row = mysqli_fetch_assoc($get_all_youtube_link)) {
	$youtube_link.= "<li>";
	$youtube_link.= "<a href='?page=new-solution&id-assignment=" . $id_assignment . "&delete_video=" . $row["id"] . "'>Zmazať</a>";
	$youtube_link.= "<span>http://www.youtube.com/watch?v=" . $row["link"] . "</span>";
	$youtube_link.= "</li>";
}
$this->set("get_youtube_videos", $youtube_link);

// delete video
if (Security::get("delete_video")) {
	$file = $this->database->remove_video(Security::get("delete_video"), $id_solution);
	header("Location: ./?page=new-solution&id-assignment=" . $id_assignment);
}


// get images
$images = "";
$get_image_solution = $this->database->get_image_solution($id_solution);
while ($row = mysqli_fetch_assoc($get_image_solution)) {
	$images.= "<li><a href=\"components/get_image.php?id=" . $row["token"] . "&image=.jpg\" rel='group'><img src='components/get_image.php?id=" . $row["token"] . "&min=1'></a>";
	$images.= "<a href='?page=new-solution&id-assignment=" . $id_assignment . "&delete_image=" . $row["token"] . "'>Zmazať</a>";
	$images.= "</li>";
}
$this->set("get_images_solution", $images);

// delete image
if (Security::get("delete_image")) {
	$file = $this->database->new_solution_remove_image(Security::get("delete_image"));
	@unlink("attachments/solutions/" . $id_solution . "/images/big/" . $file["id"] . "." . $file["extension"]);
	@unlink("attachments/solutions/" . $id_solution . "/images/small/" . $file["id"] . ".jpg");
	header("Location: ./?page=new-solution&id-assignment=" . $id_assignment);
}


// get programs
$program = "";
$get_programs_solution = $this->database->get_programs_solution($id_solution);
while ($row = mysqli_fetch_assoc($get_programs_solution)) {
	$program.= "<li><a href='components/download_attachment.php?id=" . $row["token"] . "'>" . $row["original_name"] . "</a>";
	$program.= "<a href='?page=new-solution&id-assignment=".$id_assignment."&delete_program=" . $row["token"] . "'>Zmazať</a>";
	$program.= "</li>";
}
$this->set("get_programs_solution", $program);

// delete program
if (Security::get("delete_program")) {
	$file = $this->database->new_solution_remove_program(Security::get("delete_program"));
	@unlink("attachments/solutions/" . $id_solution . "/programs/" . $file);
	header("Location: ./?page=new-solution&id-assignment=" . $id_assignment);
}

return null;

?>
