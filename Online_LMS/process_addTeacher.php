<?php  
// Teacher enrollment process
$group = $_POST["group"];
$course = $_POST["course"];
$tid = $_POST["tid"];

$connection = new mysqli("localhost","root","","online_lms");

// create php object 
$phpResponseObject = new stdClass();

// validate process
if($group=="0"){
    $phpResponseObject->msg = "Select group";
}else if($course=="0"){
    $phpResponseObject->msg = "Select course";
}else if($tid==null){
    $phpResponseObject->msg = "Enter teacher id";
}else{
    // check teacher already added or not
    $table2 = $connection->query("SELECT * FROM `user_has_group_has_course` WHERE 
    `group_has_course_group_id`='".$group."' AND `group_has_course_course_id`='".$course."' AND `user_username`='".$tid."'");
    $row = $table2->fetch_assoc();

    if($row){
        // if already added
        $phpResponseObject->msg = "Teacher already added to this course. ";
    }else{
// if not added to course then search teacher has group or not
        $table3 = $connection->query("SELECT * FROM `group_has_course` WHERE `group_id`='".$group."' AND `course_id`='".$course."'");
        $row3 = $table3->fetch_assoc();
        if(!$row3){
            // if teacher has not enroll for group
            $connection->query("INSERT INTO `group_has_course` VALUES('".$group."','".$course."')");
        }
// insert course to the group in selected teacher
            $table = $connection->query("INSERT INTO `user_has_group_has_course` VALUES('".$tid."','".$group."','".$course."','3')");

            $phpResponseObject->msg = "Succesfully Added To Course";
    }
}

$jsonResponseText = json_encode($phpResponseObject);
echo ($jsonResponseText);

?>