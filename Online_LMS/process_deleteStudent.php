<?php
$username = $_POST["username"] ?? "";
if ($username == "") {
    echo "No username!";
    exit;
}
$connection = new mysqli("localhost", "root", "", "online_lms");
$connection->query("DELETE FROM user_has_group_has_course WHERE user_username='$username'");
$connection->query("DELETE FROM user_has_group WHERE user_username='$username'");
$connection->query("DELETE FROM user WHERE username='$username'");
echo "Student deleted!";
?>