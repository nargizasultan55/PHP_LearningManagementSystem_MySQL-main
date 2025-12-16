<?php
session_start();
if (!isset($_SESSION["u"])) {
    header("Location: SignIn.php");
    exit;
}
$connection = new mysqli("localhost", "root", "", "online_lms");

// CRUD для курсов
if (isset($_POST['add'])) {
    $name = $connection->real_escape_string($_POST['name']);
    $level = intval($_POST['level']);
    $connection->query("INSERT INTO course (name, level) VALUES ('$name', $level)");
}
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $connection->real_escape_string($_POST['name']);
    $level = intval($_POST['level']);
    $connection->query("UPDATE course SET name='$name', level=$level WHERE id=$id");
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $connection->query("DELETE FROM course WHERE id=$id");
}
$courses = $connection->query("SELECT * FROM course");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | Dashboard</title>
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
                        <a href="#" class="navbar-brand text-white"><img src="system.png" class="icon2" />Learning Management System</a>
                    </div>
                    <div class="btn-group col-lg-4">
                        <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <label class="fs-6"><?php echo ($_SESSION["u"]) ?></label>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="ProfileAdmin.php">Profile</a>
                            <a class="dropdown-item" href="AdminDash.php">Dashboard</a>
                            <a class="dropdown-item" href="UserManage.php">Requests</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="SignOut.php">Sign Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Menu Bar-->
            <div class="col-4 col-lg-3 mt-5">
                <div class="row bg-primary bg-opacity-10 mt-5">
                    <!-- ...оставьте меню как есть... -->
                    <div class="col-12 mt-4">
                        <form action="AdminDash.php">
                            <button class="btn btn-outline-dark col-12 active" type="submit">Dashboard</button>
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
                        <form action="CourseManage.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Course Manage</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="GroupManage.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Group Manage</button>
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
                            <button class="btn btn-outline-dark col-12" type="submit">Mentor Enrollment</button>
                        </form>
                    </div>
                    <div class="col-12 mt-5 mb-2">
                        <form action="SignOut.php">
                            <button class="btn btn-outline-success col-12" type="submit">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-8 col-lg-9 mt-5">
                <br><br>
                <div class="row">
                    <div class="">
                        <label class="fs-2">Course Management</label>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <!-- Форма добавления/редактирования курса -->
                        <form method="post" class="row g-2 align-items-end mb-4">
                            <input type="hidden" name="id" id="course_id">
                            <div class="col-md-5">
                                <input type="text" name="name" id="course_name" class="form-control" placeholder="Course Name" required>
                            </div>
                            <div class="col-md-3">
                                <select name="level" id="course_level" class="form-control" required>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" name="add" class="btn btn-success" id="addBtn">Add</button>
                                <button type="submit" name="edit" class="btn btn-warning d-none" id="editBtn">Save</button>
                            </div>
                        </form>
                        <!-- Таблица курсов -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $courses->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= $row['level'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editCourse(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', <?= $row['level'] ?>)">Edit</button>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center text-black-50 mt-4 mb-2">
            <label>PHP_2025</label>
        </div>
    </div>
    <script>
        function editCourse(id, name, level) {
            document.getElementById('course_id').value = id;
            document.getElementById('course_name').value = name;
            document.getElementById('course_level').value = level;
            document.getElementById('addBtn').classList.add('d-none');
            document.getElementById('editBtn').classList.remove('d-none');
        }
    </script>
    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>