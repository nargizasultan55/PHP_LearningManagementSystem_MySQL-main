<?php  
// Teacher enrollment process
$groups = $_POST["groups"] ?? [];
$course = $_POST["course"] ?? "";
$tid = $_POST["tid"] ?? "";

$connection = new mysqli("localhost","root","","online_lms");

// create php object 
$phpResponseObject = new stdClass();

// validate process
if(empty($groups)) {
    $phpResponseObject->msg = "Select at least one group";
} else if(empty($course)) {
    $phpResponseObject->msg = "Select course";
} else if(empty($tid)) {
    $phpResponseObject->msg = "Enter teacher id";
} else {
    // Удаляем старые группы и курсы для этого учителя
    $connection->query("DELETE FROM user_has_group WHERE user_username='$tid'");
    $connection->query("DELETE FROM user_has_group_has_course WHERE user_username='$tid'");

    // Добавляем новые группы
    foreach ($groups as $group_id) {
        $connection->query("INSERT INTO user_has_group (user_username, group_id) VALUES ('$tid', '$group_id')");
    }

    // Добавляем курс (только один) для всех выбранных групп
    foreach ($groups as $group_id) {
        // Если связи группы и курса нет — добавляем
        $table3 = $connection->query("SELECT * FROM `group_has_course` WHERE `group_id`='$group_id' AND `course_id`='$course'");
        if(!$table3->fetch_assoc()){
            $connection->query("INSERT INTO `group_has_course` VALUES('$group_id','$course')");
        }
        // Назначаем учителя на курс в группе
        $connection->query("INSERT INTO `user_has_group_has_course` VALUES('$tid','$group_id','$course','3')");
    }

    $phpResponseObject->msg = "Successfully added teacher to selected groups and course";
}

echo json_encode($phpResponseObject);
?>