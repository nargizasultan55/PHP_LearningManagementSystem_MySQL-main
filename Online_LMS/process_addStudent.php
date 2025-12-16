<?php
$group = $_POST["group"];
$sid = $_POST["sid"];

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

    // Check if student is already enrolled in courses for the new group
    $stmt = $connection->prepare("SELECT 1 FROM user_has_group_has_course WHERE user_username=? AND group_has_course_group_id=?");
    $stmt->bind_param("si", $sid, $group);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Get all courses for the group
        $stmt2 = $connection->prepare("SELECT course_id FROM group_has_course WHERE group_id=?");
        $stmt2->bind_param("i", $group);
        $stmt2->execute();
        $courses = $stmt2->get_result();
        while ($row2 = $courses->fetch_assoc()) {
            $course = $row2["course_id"];
            // Enroll student in each course for the group
            $stmt3 = $connection->prepare("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES (?, ?, ?, 2)");
            $stmt3->bind_param("sii", $sid, $group, $course);
            $stmt3->execute();
        }
    }

    $phpResponseObject->msg = "Successfully enrolled student";
}

echo json_encode($phpResponseObject);
?>