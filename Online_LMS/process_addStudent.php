<?php
$group = $_POST["group"];
$sid = $_POST["sid"];
$course_level_1 = $_POST["course_level_1"] ?? "";
$courses_level_0 = $_POST["courses_level_0"] ?? [];

$connection = new mysqli("localhost", "root", "", "online_lms");
$phpResponseObject = new stdClass();

if ($group == "0" || empty($group)) {
    $phpResponseObject->msg = "Select group";
} else if (empty($sid)) {
    $phpResponseObject->msg = "Enter student id";
} else {
    // check if student already has a group
    $stmt = $connection->prepare("SELECT group_id FROM user_has_group WHERE user_username=?");
    $stmt->bind_param("s", $sid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row3 = $result->fetch_assoc();

    if (!$row3) {
        // if not enrolled in any group, insert
        $stmt = $connection->prepare("INSERT INTO user_has_group (user_username, group_id) VALUES (?, ?)");
        $stmt->bind_param("si", $sid, $group);
        $stmt->execute();
    } else {
        // if already in a group, update
        $currentGroup = $row3["group_id"];
        // update current group courses as completed
        $stmt = $connection->prepare("UPDATE user_has_group_has_course SET complete_status_id=1 WHERE user_username=? AND group_has_course_group_id=?");
        $stmt->bind_param("si", $sid, $currentGroup);
        $stmt->execute();
        // set new group
        $stmt = $connection->prepare("UPDATE user_has_group SET group_id=? WHERE user_username=?");
        $stmt->bind_param("is", $group, $sid);
        $stmt->execute();
    }

    // Удаляем все старые курсы для этого студента
    $stmt = $connection->prepare("DELETE FROM user_has_group_has_course WHERE user_username=?");
    $stmt->bind_param("s", $sid);
    $stmt->execute();

    // Добавляем выбранный курс level=1
    if ($course_level_1 != "") {
        $stmt = $connection->prepare("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES (?, ?, ?, 2)");
        $stmt->bind_param("sii", $sid, $group, $course_level_1);
        $stmt->execute();
    }

    // Добавляем выбранные курсы level=0 (максимум 2)
    $courses_level_0 = array_slice($courses_level_0, 0, 2);
    foreach ($courses_level_0 as $course_id) {
        $stmt = $connection->prepare("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES (?, ?, ?, 2)");
        $stmt->bind_param("sii", $sid, $group, $course_id);
        $stmt->execute();
    }

    $phpResponseObject->msg = "Successfully enrolled student";
}

echo json_encode($phpResponseObject);
?>