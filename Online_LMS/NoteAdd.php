<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Teacher | Lesson Notes</title>
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="system.png" />
</head>

<body>
    <!-- Note adding process to teacher -->
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
                            <!-- check if user log in or not -->
                            <?php session_start();
                            if (!isset($_SESSION["u"])) {
                                header("Location: SignIn.php");
                                exit;
                            }
                            ?>
                            <label class="fs-6"><?php echo ($_SESSION["u"]) ?></label>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="ProfileTeacher.php">Profile</a>
                            <a class="dropdown-item" href="TeacherDash.php">Dashboard</a>
                            <a class="dropdown-item" href="NoteAdd.php">Lesson Notes</a>
                            <a class="dropdown-item" href="AddAssignment.php">Assignments</a>
                            <a class="dropdown-item" href="ResultView.php">Answers</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="SignOut.php">Sign Out</a>
                        </div>
                    </div>

                    <!-- End Dropdown -->
                </div>
            </nav>


            <!-- Menu -->
            <div class="col-4 col-lg-3 mt-5">
                <div class="row bg-primary bg-opacity-10 mt-5">


                    <div class="col-12 mt-4">
                        <form action="TeacherDash.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Dashboard</button>
                        </form>

                    </div>
                    <div class="col-12 mt-3">
                        <form action="ProfileTeacher.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Profile</button>
                        </form>

                    </div>

                    <div class="col-12 mt-3">
                        <form action="NoteAdd.php">
                            <button class="btn btn-outline-dark col-12 active" type="submit">Lesson Notes</button>
                        </form>

                    </div>

                    <div class="col-12 mt-3 mb-4">
                        <form action="AddAssignment.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Assignments</button>
                        </form>

                    </div>
                    <div class="col-12 mt-3 mb-4">
                        <form action="ResultView.php">
                            <button class="btn btn-outline-dark col-12" type="submit">Assignment Marks</button>
                        </form>

                    </div>

                    <div class="col-12 mt-3 mb-3">

                        <form action="SignOut.php">
                            <button class="btn btn-outline-success col-12" type="submit">Sign Out</button>
                        </form>

                    </div>
                </div>
            </div>




            <div class="col-8 col-lg-9 mt-5">
                <div class="row">
                    <div class="col-8 col-lg-9 mt-5"></div>
                    <div class="">
                        <label class="fs-2">Lesson Notes</label>
                    </div>
                    <div class="">
                        <label class="fs-6 text-secondary">Teacher . Lesson Notes</label>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-lg-4 mt-3">
                                <!-- Select group -->
                                <label class="form-label text-black">Group</label>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="group">
                                    <option selected value="0">Select</option>
                                    <?php

                                    $connection = new mysqli("localhost", "root", "", "online_lms");
                                    $table = $connection->query("SELECT * FROM `group` INNER JOIN `user_has_group_has_course` ON 
                                    `group`.`id`=`user_has_group_has_course`.`group_has_course_group_id` WHERE 
                                    `user_has_group_has_course`.`user_username`='" . $_SESSION["u"] . "'");

                                    $groupid = array();
// using array to stop data duplicate in dropdown 
                                    for ($i = 0; $i < $table->num_rows; $i++) {
                                        # code...
                                        $row = $table->fetch_assoc();

                                        if (!in_array($row["id"], $groupid)) {
                                    ?>
                                            <option value="<?php echo ($row["id"]) ?>"><?php echo ($row["name"]) ?></option>
                                    <?php
                                            array_push($groupid, $row["id"]);
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-lg-4 mt-3">
                                <label class="form-label text-black">Course</label>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="course">
                                    <option selected value="0">Select</option>
                                    <?php


                                    $table2 = $connection->query("SELECT * FROM `course` INNER JOIN `user_has_group_has_course` ON 
                                    `course`.`id`=`user_has_group_has_course`.`group_has_course_course_id` WHERE 
                                    `user_has_group_has_course`.`user_username`='" . $_SESSION["u"] . "'");

                                    $courseid = array();
// using array to stop course duplicate in dropdown
                                    for ($i = 0; $i < $table2->num_rows; $i++) {
                                        # code...
                                        $row2 = $table2->fetch_assoc();

                                        if (!in_array($row2["id"], $courseid)) {
                                    ?>
                                            <option value="<?php echo ($row2["id"]) ?>"><?php echo ($row2["name"]) ?></option>
                                    <?php
                                            array_push($courseid, $row2["id"]);
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
<!-- Note id is auto generated by clicking button -->
                            <div class="col-8 col-lg-3 mt-3">
                                <label class="form-label text-black">Id</label>
                                <input type="text" class="form-control col-8" id="id" />

                            </div>
                            <div class="col-4 col-lg-1 mt-4">
                                <label class="form-label text-black"></label>
                                <button class="btn btn-info col-12" onclick="GenerateNoteId();">...</button>
                            </div>

                            <div class="col-12 col-lg-4">
                                <label class="form-label text-black">Title</label>
                                <input type="text" class="form-control" id="title" />
                            </div>

                            <div class="mb-3 col-12 col-lg-4">
                                <label for="formFile" class="form-label">Default file input example</label>
                                <input class="form-control" type="file" id="file">
                            </div>

                            <div class="col-12 col-lg-4 mt-2">
                                <label class="form-label text-black"></label>
                                <button class="btn btn-outline-primary col-12" onclick="AddNote();">Add</button>
                            </div>


                            <div class="mt-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Group</th>
                                            <th scope="col">Lesson Title</th>
                                            <th scope="col">View</th>
                                            <th scope="col">Delete</th>

                                        </tr>
                                    </thead>
                                    <tbody id="container">

                                        <?php
                                        // this table can see uploaded note and show this

                                        $table3 = $connection->query("SELECT * FROM `notes`");

                                        for ($i = 0; $i < $table3->num_rows; $i++) {
                                            # code...
                                            $row3 = $table3->fetch_assoc();

                                            $table4 = $connection->query("SELECT * FROM `group` WHERE `id`='" . $row3["group_has_course_group_id"] . "'");
                                            $row4 = $table4->fetch_assoc();
                                            $group = $row4["name"];

                                            $table5 = $connection->query("SELECT * FROM `course` WHERE `id`='" . $row3["group_has_course_course_id"] . "'");
                                            $row5 = $table5->fetch_assoc();
                                            $course = $row5["name"];



                                        ?>
<!-- data loading to the table -->
                                            <tr>
                                                <th scope="row"><?php echo ($row3["id"]) ?></th>
                                                <td><?php echo ($course) ?></td>
                                                <td><?php echo ($group) ?></td>
                                                <td><?php echo ($row3["name"]) ?></td>
                                                <!-- This button for the view notes -->
                                                <td><a href="<?php echo ($row3["note_location"]) ?>">View</a></td>
                                                <td>
                                                    <!-- this button used to delete note you added -->
                                                    <div class="deleteButton" data-id="<?php echo ($row3["id"]) ?>"><button class="btn btn-danger">Delete</button></div>
                                                </td>

                                            </tr>

                                        <?php
                                        }

                                        ?>


                                    </tbody>
                                </table>
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
    <script src="script.js"></script>

</body>

</html>