<?php
header('Content-Type: application/json');
session_start();
$assignment_id = $_POST['assignment_id'];
$connection = new mysqli("localhost", "root", "", "online_lms");
$result = $connection->query("SELECT * FROM user_has_release_assignment WHERE assignment_id='$assignment_id'");
$data = [];