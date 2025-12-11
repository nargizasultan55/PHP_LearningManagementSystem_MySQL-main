<?php  
// start the session and get username and get other details of post request 
session_start();
$username = $_SESSION["u"];
$group = $_POST["group"];
$course = $_POST["course"];
$id = $_POST["id"];
$name = $_POST["name"];

$year = $_POST["year"];
$from = $_POST["from"];
$to = $_POST["to"];

$connection = new mysqli("localhost","root","","online_lms");
// create php object
$phpResponseObject = new stdClass();

// validating details user provide process
if($group=="0"){
    $phpResponseObject->msg = "Select group";
}else if($course=="0"){
    $phpResponseObject->msg = "Select course";
}else if($id==null){
    $phpResponseObject->msg = "Enter id";
}else if($name==null){
    $phpResponseObject->msg = "Enter assignment name";
}else if($from==null){
    $phpResponseObject->msg = "Select Starting Date";
}else if($to==null){
    $phpResponseObject->msg = "Select deathline";
}else{
    $table2 = $connection->query("SELECT * FROM `group_has_course` WHERE `group_id`='".$group."' AND `course_id`='".$course."'");
    $row = $table2->fetch_assoc();
    // check if already enrolled class to add assignment

    if(!$row){
        // when not enrolled class
        $phpResponseObject->msg = "Group has not given to this course ";
    }else{
// if enrolled class
            $new_file_location = "Assignments/".$id.".pdf";

            // add assignment 
            $table = $connection->query("INSERT INTO `assignment`(`id`,`name`,`assignment_location`,
            `group_has_course_group_id`,`group_has_course_course_id`,`start_date`,`end_date`,`user_username`) 
            VALUES('".$id."','".$name."','".$new_file_location."','".$group."','".$course."','".$from."','".$to."','".$username."')");

            // To assign students to the assignment check if class olready done or not
            $table3 = $connection->query("SELECT * FROM `user_has_group_has_course` WHERE `group_has_course_group_id`='".$group."'
            AND `group_has_course_course_id`='".$course."' AND `complete_status_id`='2'");
// if not complete class
            for ($i = 0; $i < $table3->num_rows; $i++) { 
    $row3 = $table3->fetch_assoc();
    $newUsername = $row3["user_username"];

    if ($row3) {
        // Проверяем по user_username и assignment_id
        $table4 = $connection->query("SELECT * FROM `user_has_release_assignment` WHERE `user_username`='" . $newUsername . "' AND `assignment_id`='" . $id . "'");
        $row4 = $table4->fetch_assoc();

        if (!$row4) {
            // id автоинкремент, не указываем; file_path = NULL
            $connection->query("INSERT INTO `user_has_release_assignment`
                (`user_username`, `assignment_id`, `file_path`, `submitted_at`, `marks`, `mark_status_id`)
                VALUES (
                    '" . $newUsername . "',
                    '" . $id . "',
                    NULL,
                    '" . date("Y-m-d H:i:s") . "',
                    0,
                    1
                )");
        }
    }
}
            // file save to the given directory
           $file_location = $_FILES["file"]["tmp_name"];
if (!is_uploaded_file($file_location)) {
    $phpResponseObject->msg = "Файл не загружен!";
} else if (!is_dir("Assignments")) {
    $phpResponseObject->msg = "Папка Assignments не найдена!";
} else if (!move_uploaded_file($file_location, $new_file_location)) {
    $phpResponseObject->msg = "Ошибка сохранения файла!";
} else {
    $phpResponseObject->msg = "Succesfully Added Assignment";
}
    }
}

// send php response to the frontend as json
$jsonResponseText = json_encode($phpResponseObject);
echo ($jsonResponseText);

?>