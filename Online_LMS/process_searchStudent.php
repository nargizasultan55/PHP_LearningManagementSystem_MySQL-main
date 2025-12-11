
<?php
// process search student assignment details — безопасно, без дублирования вывода

$sid = trim($_POST['sid'] ?? '');

$mysqli = new mysqli('localhost', 'root', '', 'online_lms');
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit;
}

if ($sid === '') {
    // пустой результат
    echo '<tr><th scope="row"></th><td></td><td></td><td></td><td></td></tr>';
    $mysqli->close();
    exit;
}

$sql = "
    SELECT DISTINCT
        uha.assignment_id,
        uha.marks,
        uha.mark_status_id,
        g.name AS group_name
    FROM user_has_release_assignment uha
    JOIN assignment a ON uha.assignment_id = a.id
    LEFT JOIN `group` g ON a.group_has_course_group_id = g.id
    WHERE uha.user_username = ?
    ORDER BY uha.submitted_at DESC
";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    $mysqli->close();
    http_response_code(500);
    exit;
}

$stmt->bind_param('s', $sid);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $i = 0;
    while ($row = $res->fetch_assoc()) {
        $i++;
        $assignment_id = htmlspecialchars($row['assignment_id']);
        $group = htmlspecialchars($row['group_name'] ?? '');
        $marks = $row['marks'] !== null ? htmlspecialchars((string)$row['marks']) : '';
        $ms = (int)($row['mark_status_id'] ?? 0);

        echo '<tr>';
        echo '<th scope="row">'. $i .'</th>';
        echo '<td>'. $assignment_id .'</td>';
        echo '<td>'. $group .'</td>';
        echo '<td>'. $marks .'</td>';

        if ($marks !== '' && (int)$row['marks'] >= 40) {
            echo '<td><label class="text-primary">Pass</label></td>';
        } else if ($marks !== '') {
            echo '<td><label class="text-danger">Fail</label></td>';
        } else {
            echo '<td></td>';
        }

        switch ($ms) {
            case 4:
                echo '<td><label class="text-success">Released</label></td>';
                break;
            case 3:
                echo '<td><label class="text-secondary">Marking</label></td>';
                break;
            case 2:
                echo '<td><label class="text-info">Submitted</label></td>';
                break;
            case 1:
            default:
                echo '<td><label class="text-danger">Absent</label></td>';
                break;
        }

        echo '</tr>';
    }
} else {
    echo '<tr><th scope="row"></th><td></td><td></td><td></td><td></td></tr>';
}

$stmt->close();
$mysqli->close();
?>