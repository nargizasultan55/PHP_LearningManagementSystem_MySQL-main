<?php  
// Student enrollment process
$group = $_POST["group"];
$sid = $_POST["sid"];

// Get the current date
$currentDate = new DateTime();

// Change the month to the next month
$currentDate->modify('+1 month');

// Format the date as needed
$nextMonth = $currentDate->format('Y-m-d');

$connection = new mysqli("localhost","root","","online_lms");

// create php object to store massage
$phpResponseObject = new stdClass();

// validate process
if($group=="0"){
    $phpResponseObject->msg = "Select group";
}else if($sid==null){
    $phpResponseObject->msg = "Enter student id";
}else{
// check student already has group or not
        $table3 = $connection->query("SELECT * FROM `user_has_group` WHERE `user_username`='".$sid."'");
        $row3 = $table3->fetch_assoc();
        
        if(!$row3){
            // if not enrolled group insert it
            $connection->query("INSERT INTO `user_has_group` VALUES('".$sid."','".$group."','".$nextMonth."')");
        }else{
            // if group has then update it
            $currentGroup = $row3["group_id"];
// update current group
            $connection->query("UPDATE `user_has_group_has_course` SET `complete_status_id`='1' WHERE 
            `user_username`='".$sid."' AND `group_has_course_group_id`='".$currentGroup."'");
// set trail period for payments
            $connection->query("UPDATE `user_has_group` SET `group_id`='".$group."',`expire_date`='".$nextMonth."' 
            WHERE `user_username`='".$sid."'");
        }

        // search student has enroll for courses to the upgroup group
        $table4 = $connection->query("SELECT * FROM `user_has_group_has_course` WHERE `user_username`='".$sid."' 
        AND `group_has_course_group_id`='".$group."'");

        if(!$table4->num_rows){
            // if no courses
        $table2 = $connection->query("SELECT * FROM `group_has_course` WHERE `group_id`='".$group."'");

        if($table2->num_rows){
            for ($i=0; $i <$table2->num_rows ; $i++) { 
                # code...
                $row2 = $table2->fetch_assoc();
                $course = $row2["course_id"];
// add student for upgroupd class
                $table = $connection->query("INSERT INTO `user_has_group_has_course` VALUES('".$sid."','".$group."','".$course."','2')");
            }
        }
        }

            

            $phpResponseObject->msg = "Succesfully Upgrated Student";
    }


$jsonResponseText = json_encode($phpResponseObject);
echo ($jsonResponseText);

?>