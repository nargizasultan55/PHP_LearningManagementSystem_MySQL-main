<?php
$phpResponseObject = new stdClass();
$connection = new mysqli("localhost", "root", "", "online_lms");
$id = $_POST['id'];

if (!$id) {
    $phpResponseObject->msg = "No assignment id!";
} else {
    $connection->query("DELETE FROM `assignment` WHERE `id`='" . $connection->real_escape_string($id) . "'");
    if ($connection->error) {
        $phpResponseObject->msg = "Error: " . $connection->error;
    } elseif ($connection->affected_rows > 0) {
        $phpResponseObject->msg = "Assignment deleted!";
    } else {
        $phpResponseObject->msg = "Delete Failed";
    }
}
echo json_encode($phpResponseObject);
?>