<?php


// id assignment
$id = Security::get("id");

function is_image($img){
	return (bool)getimagesize($img);
}

if (Security::post("save_assignment") == "ok") {

	// update assignment
	$this->database->edit_assignment(
		Security::get("id"),
		Security::post("sk_title"),
		Security::post("en_title"),
		Security::post("de_title"),
		Security::post("sk_assignment"),
		Security::post("en_assignment"),
		Security::post("de_assignment")
	);
	
	// create dir
	@mkdir("attachments/assignments/" . $id, 0755);
	@mkdir("attachments/assignments/" . $id . "/images", 0755);
	
	// upload image
	$total = count($_FILES["upload"]["name"]);
	for($i=0; $i<$total; $i++) {
		$tmpFilePath = $_FILES["upload"]["tmp_name"][$i];

		if ($tmpFilePath != "" && is_image($_FILES["upload"]["tmp_name"][$i])){
			$extension = pathinfo($_FILES["upload"]["name"][$i], PATHINFO_EXTENSION);
			$id_image = $this->database->new_assignment_image($id, $extension);
			
			$newFilePath = "attachments/assignments/" . $id . "/images/" . $id_image . "." . $extension;
			echo $newFilePath;
			move_uploaded_file($tmpFilePath, $newFilePath);
		}
	}
	
	// save youtube link
	$url_youtube = Security::post("youtube_link");
	if (preg_match("~^(?:https?://)?(?:www\.)?(?:youtube\.com|youtu\.be)/watch\?v=([^&]+)~x", $url_youtube) == 1) {

		$parts = parse_url($url_youtube);
		parse_str($parts["query"], $query);

		$this->database->add_youtube_link(1, Security::get("id"), $query["v"]);
	}
}


// delete image
if (Security::get("delete_image")) {
	$file = $this->database->new_assignment_remove_image(Security::get("delete_image"));
	@unlink("attachments/assignments/" . $id . "/images/" . $file);
	header("Location: ./?page=new-assignment&id=" . $id);
}


// delete video
if (Security::get("delete_video")) {
	$file = $this->database->remove_video(Security::get("delete_video"), $id);
	header("Location: ./?page=new-assignment&id=" . $id);
}


// create or load assignment
if (!$id) {
	$id = $this->database->create_new_assignment($this->get("user", "id"));
	header("Location: ?page=new-assignment&id=" . $id);
} else {
	$get_assignment = $this->database->new_assignment_content($id);
	$this->set("new_assignment_id", $id);
	$this->set("new_assignment_sk_title", $get_assignment["sk_title"]);
	$this->set("new_assignment_en_title", $get_assignment["en_title"]);
	$this->set("new_assignment_de_title", $get_assignment["de_title"]);
	$this->set("new_assignment_sk_description", $get_assignment["sk_description"]);
	$this->set("new_assignment_en_description", $get_assignment["en_description"]);
	$this->set("new_assignment_de_description", $get_assignment["de_description"]);
}


// get youtube videos
$get_all_youtube_link = $this->database->get_all_youtube_link(1, $id);
$youtube_link = "";
while ($row = mysqli_fetch_assoc($get_all_youtube_link)) {
	$youtube_link.= "<li>";
	$youtube_link.= "<a href='?page=new-assignment&id=" . $id . "&delete_video=" . $row["id"] . "'>Zmazať</a>";
	$youtube_link.= "<a href='#' rel='" . $row["link"] . "' class='add_youtube_to_tiny'>Vložiť</a>";
	$youtube_link.= "<span>http://www.youtube.com/watch?v=" . $row["link"] . "</span>";
	$youtube_link.= "</li>";
}
$this->set("get_youtube_videos", $youtube_link);


// exist images
$images = "";
$get_image_assignment = $this->database->get_image_assignment($id);
while ($row = mysqli_fetch_assoc($get_image_assignment)) {
	$image_path = "attachments/assignments/" . $id . "/images/" . $row["id"] . "." . $row["extension"];

	if (!file_exists(getcwd() . "/" . $image_path))
	  $image_path = "attachments/assignments/" . $id . "/images/big/" . $row["id"] . "." . $row["extension"];

	$images.= "<li><img src='" . $image_path . "'>";
	$images.= "<a href='?page=new-assignment&id=" . $id . "&delete_image=" . $row["id"] . "'>Zmazať</a>";
	$images.= "<a href='#' rel='" . $image_path . "' class='add_image_to_tiny'>Vložiť</a>";
	$images.= "<span>" . $image_path . "</span></li>";
}
$this->set("get_images_assignment", $images);


return NULL;

?>
