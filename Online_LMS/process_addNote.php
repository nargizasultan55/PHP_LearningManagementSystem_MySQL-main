<?php  
// Note add process
$group = $_POST["group"];
$course = $_POST["course"];
$id = $_POST["id"];
$title = $_POST["title"];

$connection = new mysqli("localhost","root","","online_lms");
// create php object
$phpResponseObject = new stdClass();

// validating process
if($group=="0"){
    $phpResponseObject->msg = "Select group";
}else if($course=="0"){
    $phpResponseObject->msg = "Select course";
}else if($id==null){
    $phpResponseObject->msg = "Enter id";
}else if($title==null){
    $phpResponseObject->msg = "Enter Note name";
}else{
    $table2 = $connection->query("SELECT * FROM `group_has_course` WHERE `group_id`='".$group."' AND `course_id`='".$course."'");
    $row = $table2->fetch_assoc();
// check class has or not
    if(!$row){
        // if no class
        $phpResponseObject->msg = "Group has not enroll to this course ";
    }else{
        // if class has

            $new_file_location = "Notes/".$id.".pdf";
// insert note
            $table = $connection->query("INSERT INTO `notes` VALUES('".$id."','".$title."','".$new_file_location."','".$group."','".$course."')");
// save file to the given location
            $file_location = $_FILES["file"]["tmp_name"];
            move_uploaded_file($file_location, $new_file_location);

            $phpResponseObject->msg = "Succesfully Added Note";
    }
}

$jsonResponseText = json_encode($phpResponseObject);
echo ($jsonResponseText);

?>