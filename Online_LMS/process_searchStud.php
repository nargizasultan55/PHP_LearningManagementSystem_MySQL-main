<?php
$sid = $_POST["sid1"];
$connection = new mysqli("localhost", "root", "", "online_lms");

$table = $connection->query("SELECT * FROM `user_has_group_has_course` WHERE `user_username`='".$sid."'");

if ($table->num_rows) {
    for ($i = 0; $i < $table->num_rows; $i++) { 
        $row = $table->fetch_assoc();

        $table2 = $connection->query("SELECT * FROM `course` WHERE `id`='".$row["group_has_course_course_id"]."'");
        $row2 = $table2->fetch_assoc();
        $course = $row2["name"];

        $table3 = $connection->query("SELECT * FROM `group` WHERE `id`='".$row["group_has_course_group_id"]."'");
        $row3 = $table3->fetch_assoc();
        $group = $row3["name"];

        $status_id = $row["complete_status_id"];
        if ($status_id == 1) $status = "Completed";
        elseif ($status_id == 2) $status = "In Progress";
        elseif ($status_id == 3) $status = "Checked";
        else $status = "Unknown";

        echo "<tr>
            <th scope='row'>".($i+1)."</th>
            <td>".$row["user_username"]."</td>
            <td>".$group."</td>
            <td>".$course."</td>
            <td>".$status."</td>
        </tr>";
    }
} else {
    echo "<tr>
        <th scope='row'></th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>";
}
?>