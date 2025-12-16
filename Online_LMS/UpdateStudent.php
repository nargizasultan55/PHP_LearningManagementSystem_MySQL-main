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

// Получаем текущие данные студента
$user = $connection->query("SELECT * FROM user WHERE username='$username'")->fetch_assoc();
$user_group = $connection->query("SELECT group_id FROM user_has_group WHERE user_username='$username'")->fetch_assoc();
$current_group = $user_group["group_id"] ?? "";

// Получаем id курсов, на которые студент уже записан
$student_courses = [];
$res = $connection->query("SELECT group_has_course_course_id FROM user_has_group_has_course WHERE user_username='$username'");
while ($row = $res->fetch_assoc()) {
    $student_courses[] = $row["group_has_course_course_id"];
}

// Обработка формы

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_group = $_POST["group"] ?? "";
    $course_level_1 = $_POST["course_level_1"] ?? "";
    $courses_level_0 = $_POST["courses_level_0"] ?? [];

    // Обновляем группу
    if ($new_group != "") {
        $connection->query("UPDATE user_has_group SET group_id='$new_group' WHERE user_username='$username'");
    }

    // Удаляем все старые курсы
    $connection->query("DELETE FROM user_has_group_has_course WHERE user_username='$username'");

    // Добавляем выбранный курс level=1
    if ($course_level_1 != "") {
        $connection->query("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES ('$username', '$new_group', '$course_level_1', 1)");
    }

    // Добавляем выбранные курсы level=0 (максимум 2)
    $courses_level_0 = array_slice($courses_level_0, 0, 2);
    foreach ($courses_level_0 as $course_id) {
        $connection->query("INSERT INTO user_has_group_has_course (user_username, group_has_course_group_id, group_has_course_course_id, complete_status_id) VALUES ('$username', '$new_group', '$course_id', 1)");
    }

    header("Location: StudentEnrollment.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Student</title>
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="system.png" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-light navbar-expand-lg bg-dark fixed-top">
                <div class="container-fluid">
                    <div class="col-4">
                        <a href="#" class="navbar-brand text-white"><img src="system.png" class="icon2" />Learning
                            Management System</a>
                    </div>
                    <!-- Dropdown -->
                    <div class="btn-group col-lg-4">
                        <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo '<label class="fs-6">' . htmlspecialchars($_SESSION["u"]) . '</label>'; ?>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="ProfileAdmin.php">Profile</a>
                            <a class="dropdown-item" href="AdminDash.php">Dashboard</a>
                            <a class="dropdown-item" href="UserManage.php">Requests</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="SignOut.php">Sign Out</a>
                        </div>
                    </div>
                    <!-- End Dropdown -->
                </div>
            </nav>
            <!-- Левое меню -->
            <div class="col-4 col-lg-3 mt-5">
                <div class="row bg-primary bg-opacity-10 mt-5">
                    <div class="col-12 mt-4">
                        <form action="AdminDash.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Dashboard</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="ProfileAdmin.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Profile</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="UserManage.php">
                            <button class="btn btn-outline-dark col-12" type="submit">User Manage</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="ResultView.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Results</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="StudentEnrollment.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Student Enrollment</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="TeacherEnrollment.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Teacher Enrollment</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="AccademicEnrollment.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Accademic Officer Enrollment</button>
                        </form>
                    </div>
                    <div class="col-12 mt-5 mb-2">
                        <form action="SignOut.php">
                            <button class="btn btn-outline-success col-12" type="submit">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Правая часть: форма редактирования -->
            <div class="col-8 col-lg-9 mt-5">
                <div class="row">
                    <div class="col-12 mt-5"></div>
                    <div class="">
                        <label class="fs-2">Update Student</label>
                    </div>
                    <div class="">
                        <label class="fs-6 text-secondary">Admin . Update Student</label>
                    </div>
                    <div class="col-12">
                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white">
                                Update Student: <?php echo htmlspecialchars($username); ?>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Group</label>
                                        <select name="group" class="form-select">
                                            <?php
                                            $groups2 = $connection->query("SELECT * FROM `group`");
                                            while ($g = $groups2->fetch_assoc()) { ?>
                                                <option value="<?php echo $g["id"]; ?>" <?php if ($g["id"] == $current_group) echo "selected"; ?>>
                                                    <?php echo htmlspecialchars($g["name"]); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">Courses</label>
                                        <?php
                                        // Получаем все курсы с их level
                                        $all_courses2 = $connection->query("SELECT * FROM course");
                                        $courses_level_1 = [];
                                        $courses_level_0 = [];
                                        while ($c = $all_courses2->fetch_assoc()) {
                                            if ($c["level"] == 1) {
                                                $courses_level_1[] = $c;
                                            } else {
                                                $courses_level_0[] = $c;
                                            }
                                        }
                                        ?>
                                        <label class="form-label">Level 1 (only one course):</label>
                                        <select name="course_level_1" class="form-select mb-2">
                                            <option value="">-- Not selected --</option>
                                            <?php foreach ($courses_level_1 as $c) { ?>
                                                <option value="<?php echo $c["id"]; ?>" <?php if (in_array($c["id"], $student_courses)) echo "selected"; ?>>
                                                    <?php echo htmlspecialchars($c["name"]); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <label class="form-label">Level 0 (you can select up to two):</label>
                                        <select name="courses_level_0[]" class="form-select" multiple size="3" id="courses_level_0">
                                            <?php foreach ($courses_level_0 as $c) { ?>
                                                <option value="<?php echo $c["id"]; ?>" <?php if (in_array($c["id"], $student_courses)) echo "selected"; ?>>
                                                    <?php echo htmlspecialchars($c["name"]); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <small class="text-muted"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="StudentEnrollment.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center text-black-50 mt-4 mb-2">
            <label>PHP_2025</label>
        </div>
    </div>
    <script src="bootstrap.bundle.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var select = document.getElementById("courses_level_0");
    select.addEventListener("change", function() {
        let selected = Array.from(select.selectedOptions);
        if (selected.length > 2) {
            selected[selected.length - 1].selected = false;
            alert("Можно выбрать не более двух курсов для Level 0!");
        }
    });
});
</script>
</body>

</html>