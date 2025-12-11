
<?php 
// upload assignment process

$id = $_POST["id"];
$Answer_location = $_FILES["assAnswer"]["tmp_name"];

$connection = new mysqli("localhost","root","","online_lms");

$file_location = "Answers/".$id.".pdf";

$connection->query("UPDATE `user_has_release_assignment` SET `file_path`='".$file_location."',
`submitted_at`='".date("Y-m-d H:i:s")."',`mark_status_id`='2' WHERE `id`='".$id."'");

// save uploaded file
move_uploaded_file($Answer_location, $file_location);

echo("Submitted Successfully");

?>