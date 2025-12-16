<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teacher Enrollment</title>
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
                        <a href="#" class="navbar-brand text-white">
                            <img src="system.png" class="icon2" />Learning Management System
                        </a>
                    </div>
                    <div class="btn-group col-lg-4">
                        <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php session_start();
                            if (!isset($_SESSION["u"])) {
                                header("Location: SignIn.php");
                                exit;
                            }
                            ?>
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
            <!-- Menu -->
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
                            <button class="btn btn-outline-dark col-12 active" type="submit">Teacher Enrollment</button>
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
            <!-- Menu end -->
            <div class="col-8 col-lg-9 mt-5">
                <div class="row">
                    <div class="col-8 col-lg-9 mt-5"></div>
                    <div>
                        <label class="fs-2">Teacher Enrollment</label>
                    </div>
                    <div>
                        <label class="fs-6 text-secondary">Admin . Teacher Enrollment</label>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-lg-12 mt-4">
                                <label class="form-label text-black">Search Teacher</label>
                                <input class="col-12 mb-3" type="search" placeholder="Username" aria-label="Search" id="searchTeacher" onkeyup="SearchTeacher();">
                                <form id="teacherEnrollForm" onsubmit="TeacherEnrollment(); return false;">
                                    <div class="mb-3">
                                        <label class="form-label text-black">Groups (multiple):</label>
                                        <select id="groups" name="groups[]" class="form-select" multiple size="5">
                                            <?php
                                            $connection = new mysqli("localhost", "root", "", "online_lms");
                                            $groups = $connection->query("SELECT * FROM `group`");
                                            while ($g = $groups->fetch_assoc()) {
                                                echo '<option value="' . $g["id"] . '">' . htmlspecialchars($g["name"]) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <small class="text-muted"></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-black">Course (only one):</label>
                                        <select id="course" name="course" class="form-select">
                                            <option value="">Select</option>
                                            <?php
                                            $courses = $connection->query("SELECT * FROM course WHERE level=1");
                                            while ($c = $courses->fetch_assoc()) {
                                                echo '<option value="' . $c["id"] . '">' . htmlspecialchars($c["name"]) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary col-12">Add</button>
                                </form>
                            </div>
                            <div class="mt-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Teacher Id</th>
                                            <th scope="col">Group</th>
                                            <th scope="col">Course</th>
                                        </tr>
                                    </thead>

                                    <tbody id="container">
                                        <?php include 'process_allTeachers.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- ...остальной код... -->
                        <script src="bootstrap.bundle.js"></script>
                        <script src="script.js"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", ShowAllTeachers);
                        </script>
</body>

</html>