
<?php
session_start();
if (!isset($_SESSION["u"])) {
    header("Location: SignIn.php");
    exit;
}

$connection = new mysqli("localhost", "root", "", "online_lms");

$username = $_GET["username"] ?? "";
if ($username == "") {
    echo "No username specified!";
    exit;
}

// Получаем текущие группы учителя
$teacher_groups = [];
$res = $connection->query("SELECT group_id FROM user_has_group WHERE user_username='$username'");
while ($row = $res->fetch_assoc()) {
    $teacher_groups[] = $row["group_id"];
}

// Получаем текущий курс учителя (level=1)
$teacher_course = "";
$res = $connection->query("SELECT group_has_course_course_id FROM user_has_group_has_course WHERE user_username='$username' LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $teacher_course = $row["group_has_course_course_id"];
}

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_groups = $_POST["groups"] ?? [];
    $new_course = $_POST["course"] ?? "";

    // Удаляем все старые группы
    $connection->query("DELETE FROM user_has_group WHERE user_username='$username'");
    // Добавляем новые группы
    foreach ($new_groups as $group_id) {
        $connection->query("INSERT INTO user_has_group (user_username, group_id) VALUES ('$username', '$group_id')");
    }

    // Удаляем все старые курсы
    $connection->query("DELETE FROM user_has_group_has_course WHERE user_username='$username'");
    // Добавляем выбранный курс (только один)
    if ($new_course != "") {
        foreach ($new_groups as $group_id) {
            $connection->query("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES ('$username', '$group_id', '$new_course', 3)");
        }
    }

    header("Location: TeacherEnrollment.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Teacher</title>
    <link rel="stylesheet" href="bootstrap.css" />
</head>
<body>
<div class="container mt-5">
    <h3>Update Teacher: <?php echo htmlspecialchars($username); ?></h3>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Groups (multiple):</label>
            <select name="groups[]" class="form-select" multiple size="5">
                <?php
                $groups = $connection->query("SELECT * FROM `group`");
                while ($g = $groups->fetch_assoc()) { ?>
                    <option value="<?php echo $g["id"]; ?>" <?php if (in_array($g["id"], $teacher_groups)) echo "selected"; ?>>
                        <?php echo htmlspecialchars($g["name"]); ?>
                    </option>
                <?php } ?>
            </select>
            <small class="text-muted">Hold Ctrl/Cmd to select multiple groups</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Course (only one):</label>
            <select name="course" class="form-select">
                <option value="">-- Not selected --</option>
                <?php
                $courses = $connection->query("SELECT * FROM course WHERE level=1");
                while ($c = $courses->fetch_assoc()) { ?>
                    <option value="<?php echo $c["id"]; ?>" <?php if ($c["id"] == $teacher_course) echo "selected"; ?>>
                        <?php echo htmlspecialchars($c["name"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="TeacherEnrollment.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>