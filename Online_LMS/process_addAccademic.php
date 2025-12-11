<?php  
// Mentor enrollment backend part
// get data from the post request
$group = $_POST["group"];
$aid = $_POST["aid"];
// mysql connection create
$connection = new mysqli("localhost","root","","online_lms");
// create php response object
$phpResponseObject = new stdClass();
// validation part
if($group=="0"){
    $phpResponseObject->msg = "Select group";
}else if($aid==null){
    $phpResponseObject->msg = "Enter teacher id";
}else{
// search if user is in the Mentor type
    $table4 = $connection->query("SELECT * FROM `user` WHERE `username`='".$aid."' 
        AND `user_type_id`='4'");
    
        if(!$table4->num_rows){
            // if not Mentor with given username
            $phpResponseObject->msg = "Invalid Mentor Id";
        }else{
            // check Mentor already enroll for group
        $table3 = $connection->query("SELECT * FROM `user_has_group` WHERE `user_username`='".$aid."'");
        $row3 = $table3->fetch_assoc();
        if(!$row3){
            // There is no row found insert officer to group
            $connection->query("INSERT INTO `user_has_group` VALUES('".$aid."','".$group."','".date("Y-m-d")."')");
        }else{
            // if already has group update officer
            $connection->query("UPDATE `user_has_group` SET `group_id`='".$group."',`expire_date`='".date("Y-m-d")."' 
            WHERE `user_username`='".$aid."'");
        }
        
            $phpResponseObject->msg = "Succesfully Added Officer";
    }
    }

// convert php object to the json text and send it
$jsonResponseText = json_encode($phpResponseObject);
echo ($jsonResponseText);

?>