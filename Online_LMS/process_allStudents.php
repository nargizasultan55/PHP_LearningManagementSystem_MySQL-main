<?php
$connection = new mysqli("localhost", "root", "", "online_lms");
$table = $connection->query("SELECT * FROM user_has_group WHERE user_username IN (SELECT username FROM user WHERE user_type_id=3)");
$i = 0;
while ($row = $table->fetch_assoc()) {
    $i++;
    $username = htmlspecialchars($row["user_username"]);
    $group_id = $row["group_id"];

    // Получаем имя группы
    $g = $connection->query("SELECT name FROM `group` WHERE id='$group_id'");
    $group = $g->fetch_assoc()["name"] ?? "";

    // Получаем курсы
    $courses = [];
    $c = $connection->query("SELECT group_has_course_course_id FROM user_has_group_has_course WHERE user_username='$username'");
    while ($cr = $c->fetch_assoc()) {
        $cid = $cr["group_has_course_course_id"];
        $cname = $connection->query("SELECT name FROM course WHERE id='$cid'")->fetch_assoc()["name"] ?? "";
        if ($cname) $courses[] = $cname;
    }
    $courses_str = implode(", ", $courses);

    echo "<tr>
        <th scope='row'>$i</th>
        <td>$username</td>
        <td>$group</td>
        <td>$courses_str</td>
        <td>
            <button class='btn btn-sm btn-warning updateStudentBtn' data-username='$username'>Update</button>
            <button class='btn btn-sm btn-danger deleteStudentBtn' data-username='$username'>Delete</button>
        </td>
    </tr>";
}
if ($i == 0) {
    echo "<tr><th scope='row'></th><td></td><td></td><td></td><td></td></tr>";
}
?>