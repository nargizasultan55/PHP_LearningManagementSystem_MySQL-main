<?php 
// load request assignment
    session_start();
    $username = $_SESSION["u"];

    $assId = $_POST["assId"];

    $phpResponseObject = new stdClass();


            $connection = new mysqli("localhost","root","","online_lms");
            $table = $connection->query("SELECT * FROM `assignment` WHERE `id`='".$assId."' AND `user_username`='".$username."'");

                $row = $table->fetch_assoc();

                if($row){

                    $phpResponseObject->group = $row["group_has_course_group_id"];
                    $phpResponseObject->course = $row["group_has_course_course_id"];
                    $phpResponseObject->name = $row["name"];
                    $phpResponseObject->from = $row["from"];
                    $phpResponseObject->to = $row["to"];
                    $phpResponseObject->msg = "success";
                    
                }else{
                    $phpResponseObject->group = "0";
                    $phpResponseObject->course = "0";
                    $phpResponseObject->name = "";
                    $phpResponseObject->from = date("Y-m-d");
                    $phpResponseObject->to = "";
                }

    $jsonResponseText = json_encode($phpResponseObject);
    echo ($jsonResponseText);
?>