<?php
session_start();
if (!isset($_SESSION["u"])) {
    header("Location: SignIn.php");
    exit;
}
$connection = new mysqli("localhost", "root", "", "online_lms");

// CRUD для group
if (isset($_POST['add'])) {
    $name = $connection->real_escape_string($_POST['name']);
    $expire_date = date('Y-m-d', strtotime('+9 months'));
    $connection->query("INSERT INTO `group` (name, expire_date) VALUES ('$name', '$expire_date')");
}
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $connection->real_escape_string($_POST['name']);
    // expire_date не меняем при редактировании
    $connection->query("UPDATE `group` SET name='$name' WHERE id=$id");
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['id']);
    $connection->query("DELETE FROM `group` WHERE id=$id");
}

// Получаем список групп
$groups = $connection->query("SELECT * FROM `group`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Group Management</title>
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="system.png" />
</head>
<body>
    <div class="container-fluid">
        <div class="row">

        
            <!-- Menu Bar -->
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
                        <form action="CourseManage.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Course Manage</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <form action="GroupManage.php">
                            <button class="btn btn-outline-dark col-12 active" type="submit">Group Manage</button>
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
                        <label class="fs-2">Group Management</label>
                    </div>
                    <div class="col-12 mt-4">
                        <!-- Форма добавления/редактирования группы -->
                        <form method="post" class="row g-2 align-items-end mb-4">
                            <input type="hidden" name="id" id="group_id">
                            <div class="col-md-6">
                                <input type="text" name="name" id="group_name" class="form-control" placeholder="Group Name" required>
                            </div>
                            <div class="col-md-6 d-flex gap-2">
                                <button type="submit" name="add" class="btn btn-success" id="addBtn">Add</button>
                                <button type="submit" name="edit" class="btn btn-warning d-none" id="editBtn">Save</button>
                            </div>
                        </form>
                        <!-- Таблица групп -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Expire Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $groups->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['expire_date']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editGroup(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>')">Edit</button>
                                            <a href="?delete=1&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this group?')">Delete</a>
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
        function editGroup(id, name) {
            document.getElementById('group_id').value = id;
            document.getElementById('group_name').value = name;
            document.getElementById('addBtn').classList.add('d-none');
            document.getElementById('editBtn').classList.remove('d-none');
        }
    </script>
    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>
</html>