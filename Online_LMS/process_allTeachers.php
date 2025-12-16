
<?php
$connection = new mysqli("localhost", "root", "", "online_lms");

// Получаем всех учителей
$teachers = $connection->query("SELECT username FROM user WHERE user_type_id=2");
$i = 0;
while ($t = $teachers->fetch_assoc()) {
    $i++;
    $username = htmlspecialchars($t["username"]);

    // Получаем все группы учителя
    $groups = [];
    $group_ids = [];
    $g = $connection->query("SELECT group_id FROM user_has_group WHERE user_username='$username'");
    while ($gr = $g->fetch_assoc()) {
        $group_ids[] = $gr["group_id"];
        $group_name = $connection->query("SELECT name FROM `group` WHERE id='".$gr["group_id"]."'")->fetch_assoc()["name"] ?? "";
        if ($group_name) $groups[] = $group_name;
    }
    $groups_str = implode(", ", $groups);

    // Получаем курс (по ТЗ — только один, берем первый для этого учителя)
    $course_name = "";
    if (count($group_ids) > 0) {
        // Берём курс для первой группы (или можно для всех групп, если нужно)
        $gid = $group_ids[0];
        $c = $connection->query("SELECT group_has_course_course_id FROM user_has_group_has_course WHERE user_username='$username' AND group_has_course_group_id='$gid' LIMIT 1");
        if ($cr = $c->fetch_assoc()) {
            $cid = $cr["group_has_course_course_id"];
            $course_name = $connection->query("SELECT name FROM course WHERE id='$cid'")->fetch_assoc()["name"] ?? "";
        }
    }

    echo "<tr>
        <th scope='row'>$i</th>
        <td>$username</td>
        <td>$groups_str</td>
        <td>$course_name</td>
        <td>
            <button class='btn btn-sm btn-warning updateTeacherBtn' data-username='$username'>Update</button>
            <button class='btn btn-sm btn-danger deleteTeacherBtn' data-username='$username'>Delete</button>
        </td>
    </tr>";
}
if ($i == 0) {
    echo "<tr><th scope='row'></th><td></td><td></td><td></td><td></td></tr>";
}
?>