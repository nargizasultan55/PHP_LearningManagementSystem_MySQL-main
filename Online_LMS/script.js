function SignIn() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    var form = new FormData();
    form.append("username", username);
    form.append("password", password);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            try {
                var jsonResponseText = request.responseText;
                var jsResponseObject = JSON.parse(jsonResponseText);

                if (jsResponseObject.type == "Admin") {
                    window.location = "AdminDash.php";
                } else if (jsResponseObject.type == "Teacher") {
                    window.location = "TeacherDash.php";
                } else if (jsResponseObject.type == "Student") {
                    window.location = "StudentDash.php";
                } else if (jsResponseObject.type == "Accademic") {
                    window.location = "AccademicDash.php";
                } else if (jsResponseObject.type == "Verify") {
                    window.location = "Verify.php";
                } else if (jsResponseObject.type == "Payment") {
                    window.location = "Payment.php";
                } else {
                    document.getElementById("message").innerHTML = jsResponseObject.msg;
                }
            } catch (e) {
                // Response is not valid JSON
                document.getElementById("message").innerHTML = request.responseText;
            }
        }
    };
    request.open("POST", "process_signIn.php", true);
    request.send(form);

}

function SendRequest() {
    var userType = document.getElementById("userType").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var mobile = document.getElementById("mobile").value;
    var email = document.getElementById("email").value;
    var address1 = document.getElementById("address1").value;
    var address2 = document.getElementById("address2").value;
    var city = document.getElementById("city").value;
    var gender = document.getElementById("gender").value;

    var form = new FormData();
    form.append("userType", userType);
    form.append("fname", fname);
    form.append("lname", lname);
    form.append("mobile", mobile);
    form.append("email", email);
    form.append("address1", address1);
    form.append("address2", address2);
    form.append("city", city);
    form.append("gender", gender);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.getElementById("message").innerHTML = request.responseText;
            // alert("request.responseText");
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            if (jsResponseObject.msg == "success") {
                alert("Your request send to the officers. After checking your details will send email to you. Thank you for your request!");
                window.location = "SignIn.php";
            }
        }

    };
    request.open("POST", "process_sendRequest.php", true);
    request.send(form);

}

function GetUserDetails() {

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            // alert(jsResponseObject.username);
            document.getElementById("username").value = jsResponseObject.username;
            document.getElementById("password").value = jsResponseObject.password;
            document.getElementById("fname").value = jsResponseObject.fname;
            document.getElementById("lname").value = jsResponseObject.lname;
            document.getElementById("mobile").value = jsResponseObject.mobile;
            document.getElementById("email").value = jsResponseObject.email;
            document.getElementById("address1").value = jsResponseObject.address1;
            document.getElementById("address2").value = jsResponseObject.address2;
            document.getElementById("city").value = jsResponseObject.city;
            document.getElementById("gender").value = jsResponseObject.gender;

        }

    };
    request.open("POST", "process_userDetails.php", true);
    request.send();

}

function LoadGuardian() {

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            // alert(jsResponseObject.gfname);
            document.getElementById("gfname").value = jsResponseObject.gfname;
            document.getElementById("glname").value = jsResponseObject.glname;
            document.getElementById("gmobile").value = jsResponseObject.gmobile;
            document.getElementById("nic").value = jsResponseObject.nic;
        }

    };
    request.open("POST", "process_loadGuardian.php", true);
    request.send();

}

function UpdateProfile() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var mobile = document.getElementById("mobile").value;
    var email = document.getElementById("email").value;
    var address1 = document.getElementById("address1").value;
    var address2 = document.getElementById("address2").value;
    var city = document.getElementById("city").value;
    var gender = document.getElementById("gender").value;

    var form = new FormData();
    form.append("username", username);
    form.append("password", password);
    form.append("fname", fname);
    form.append("lname", lname);
    form.append("mobile", mobile);
    form.append("email", email);
    form.append("address1", address1);
    form.append("address2", address2);
    form.append("city", city);
    form.append("gender", gender);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success");
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);
            GetUserDetails();
        }

    };
    request.open("POST", "process_updateProfile.php", true);
    request.send(form);

}

function UpdateGuardian() {
    var gfname = document.getElementById("gfname").value;
    var glname = document.getElementById("glname").value;
    var gmobile = document.getElementById("gmobile").value;
    var nic = document.getElementById("nic").value;

    var form = new FormData();
    form.append("gfname", gfname);
    form.append("glname", glname);
    form.append("gmobile", gmobile);
    form.append("nic", nic);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success");
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);

            if (jsResponseObject.msg == "Successfully Updated") {
                LoadGuardian();
            }

        }

    };
    request.open("POST", "process_updateGuardian.php", true);
    request.send(form);

}

function LoadRequests() {
    var requestId = document.getElementById("requestId").value;
    document.getElementById("userId").value = "";
    var createbtn = document.getElementById("create");
    createbtn.removeAttribute("disabled", "");
    document.getElementById("status").value = "";

    var form = new FormData();
    form.append("requestId", requestId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            // alert(jsResponseObject.username);
            document.getElementById("username").value = jsResponseObject.username;
            document.getElementById("userType").value = jsResponseObject.usertype;
            document.getElementById("fname").value = jsResponseObject.fname;
            document.getElementById("lname").value = jsResponseObject.lname;
            document.getElementById("mobile").value = jsResponseObject.mobile;
            document.getElementById("email").value = jsResponseObject.email;
            document.getElementById("address1").value = jsResponseObject.address1;
            document.getElementById("address2").value = jsResponseObject.address2;
            document.getElementById("city").value = jsResponseObject.city;
            document.getElementById("password").value = jsResponseObject.password;
            document.getElementById("gender").value = jsResponseObject.gender;

        }

    };
    request.open("POST", "process_loadRequests.php", true);
    request.send(form);

}

function LoadUsers() {
    var userId = document.getElementById("userId").value;
    document.getElementById("requestId").value = "0";
    var createbtn = document.getElementById("create");
    createbtn.setAttribute("disabled", "");
    var status = document.getElementById("status").value;
    // alert("success");
    var form = new FormData();
    form.append("userId", userId);
    form.append("status", status);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            // alert("success");
            document.getElementById("username").value = jsResponseObject.username;
            document.getElementById("userType").value = jsResponseObject.usertype;
            document.getElementById("fname").value = jsResponseObject.fname;
            document.getElementById("lname").value = jsResponseObject.lname;
            document.getElementById("mobile").value = jsResponseObject.mobile;
            document.getElementById("email").value = jsResponseObject.email;
            document.getElementById("address1").value = jsResponseObject.address1;
            document.getElementById("address2").value = jsResponseObject.address2;
            document.getElementById("city").value = jsResponseObject.city;
            document.getElementById("password").value = jsResponseObject.password;
            document.getElementById("gender").value = jsResponseObject.gender;
            document.getElementById("status").value = jsResponseObject.status;

        }

    };
    request.open("POST", "process_loadUsers.php", true);
    request.send(form);

}

function CreateUser() {
    var userType = document.getElementById("userType").value;
    var username = document.getElementById("username").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var mobile = document.getElementById("mobile").value;
    var email = document.getElementById("email").value;
    var address1 = document.getElementById("address1").value;
    var address2 = document.getElementById("address2").value;
    var city = document.getElementById("city").value;
    var password = document.getElementById("password").value;
    var gender = document.getElementById("gender").value;

    var requestId = document.getElementById("requestId").value;

    var form = new FormData();
    form.append("userType", userType);
    form.append("username", username);
    form.append("fname", fname);
    form.append("lname", lname);
    form.append("mobile", mobile);
    form.append("email", email);
    form.append("address1", address1);
    form.append("address2", address2);
    form.append("city", city);
    form.append("password", password);
    form.append("gender", gender);
    form.append("requestId", requestId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            // alert("request.responseText");
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);

            if (jsResponseObject.msg == "Successfully created account") {
                location.reload();
            }
        }

    };
    request.open("POST", "process_createAcc.php", true);
    request.send(form);

}

function DisableUser() {
    var username = document.getElementById("username").value;

    var form = new FormData();
    form.append("username", username);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success"); 
            alert(request.responseText);

            if (request.responseText == "Disabled User Succesfully") {
                location.reload();
            }

        }

    };
    request.open("POST", "process_disableUser.php", true);
    request.send(form);

}

function EnableUser() {
    var username = document.getElementById("username").value;

    var form = new FormData();
    form.append("username", username);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success"); 
            alert(request.responseText);

            if (request.responseText == "Activated User Succesfully") {
                location.reload();
            }

        }

    };
    request.open("POST", "process_enableUser.php", true);
    request.send(form);

}

function AddAssignment() {
    var group = document.getElementById("group").value;
    var course = document.getElementById("course").value;
    var id = document.getElementById("id").value;
    var name = document.getElementById("name").value;
    var file = document.getElementById("file").files[0];
    var year = document.getElementById("year").value;
    var from = document.getElementById("from").value;
    var to = document.getElementById("to").value;

    // alert(file);
    if (file === undefined) {
        alert("Select Assignment File(.pdf)");
    } else {
        var form = new FormData();
        form.append("group", group);
        form.append("course", course);
        form.append("id", id);
        form.append("name", name);
        form.append("file", file);
        form.append("year", year);
        form.append("from", from);
        form.append("to", to);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                
                var jsonResponseText = request.responseText;
                var jsResponseObject = JSON.parse(jsonResponseText);

                alert(jsResponseObject.msg);

                if (jsResponseObject.msg == "Succesfully Added Assignment") {
                    location.reload();
                }
            }

        };
        request.open("POST", "process_addAssignment.php", true);
        request.send(form);
    }
}

function GenerateAssId() {
    var group = document.getElementById("group").value;
    var course = document.getElementById("course").value;
    var year = document.getElementById("year").value;

    var form = new FormData();
    form.append("group", group);
    form.append("course", course);
    form.append("year", year);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success"); 
            document.getElementById("id").value = request.responseText;

        }

    };
    request.open("POST", "process_AssId.php", true);
    request.send(form);
}

function LoadAssignment() {
    var assId = document.getElementById("id").value;

    var form = new FormData();
    form.append("assId", assId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);
            // alert("success");
            document.getElementById("group").value = jsResponseObject.group;
            document.getElementById("course").value = jsResponseObject.course;
            document.getElementById("name").value = jsResponseObject.name;
            document.getElementById("from").value = jsResponseObject.from;
            document.getElementById("to").value = jsResponseObject.to;

            if (jsResponseObject.msg == "success") {
                var ugroup = document.getElementById("group");
                ugroup.setAttribute("disabled", "");
                var ucourse = document.getElementById("course");
                ucourse.setAttribute("disabled", "");
                var ubtn = document.getElementById("add");
                ubtn.setAttribute("disabled", "");
            } else {
                var ugroup = document.getElementById("group");
                ugroup.removeAttribute("disabled", "");
                var ucourse = document.getElementById("course");
                ucourse.removeAttribute("disabled", "");
                var ubtn = document.getElementById("add");
                ubtn.removeAttribute("disabled", "");
            }

        }

    };
    request.open("POST", "process_loadReqAss.php", true);
    request.send(form);
}

function UpdateAssignment() {

    var id = document.getElementById("id").value;
    var name = document.getElementById("name").value;
    var file = document.getElementById("file").files[0];
    var from = document.getElementById("from").value;
    var to = document.getElementById("to").value;
    var group = document.getElementById("group").value;
    var course = document.getElementById("course").value;

    // alert(file);
    if (file === undefined) {
        alert("Select Assignment File(.pdf)");
    } else {
        var form = new FormData();
        form.append("id", id);
        form.append("name", name);
        form.append("file", file);
        form.append("from", from);
        form.append("to", to);
        form.append("group", group);
        form.append("course", course);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                // alert("request.responseText");
                var jsonResponseText = request.responseText;
                var jsResponseObject = JSON.parse(jsonResponseText);

                alert(jsResponseObject.msg);

                if (jsResponseObject.msg == "Succesfully Updated Assignment") {
                    location.reload();
                }
            }

        };
        request.open("POST", "process_UpdateAss.php", true);
        request.send(form);
    }
}


function GenerateNoteId() {
    var group = document.getElementById("group").value;
    var course = document.getElementById("course").value;

    var form = new FormData();
    form.append("group", group);
    form.append("course", course);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert("success"); 
            document.getElementById("id").value = request.responseText;

        }

    };
    request.open("POST", "process_noteId.php", true);
    request.send(form);
}

function AddNote() {
    var group = document.getElementById("group").value;
    var course = document.getElementById("course").value;
    var id = document.getElementById("id").value;
    var title = document.getElementById("title").value;
    var file = document.getElementById("file").files[0];

    // alert("success");
    if (file === undefined) {
        alert("Select Note File(.pdf)");
    } else {
        var form = new FormData();
        form.append("group", group);
        form.append("course", course);
        form.append("id", id);
        form.append("title", title);
        form.append("file", file);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                // alert("request.responseText");
                var jsonResponseText = request.responseText;
                var jsResponseObject = JSON.parse(jsonResponseText);

                alert(jsResponseObject.msg);

                if (jsResponseObject.msg == "Succesfully Added Note") {
                    location.reload();
                }
            }

        };
        request.open("POST", "process_addNote.php", true);
        request.send(form);
    }
}

function LoadNotes() {
    var course = document.getElementById("course").value;
    // alert(course);

    var form = new FormData();
    form.append("course", course);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText); 
            document.getElementById("loadnote").innerHTML = request.responseText;

        }

    };
    request.open("POST", "process_loadNotes.php", true);
    request.send(form);
}

document.addEventListener("DOMContentLoaded", function () {
    var deleteButtons = document.querySelectorAll(".deleteButton");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            var rowId = button.getAttribute("data-id");
            console.log("Delete button clicked for row with ID: " + rowId);

            // alert(rowId);
            var form = new FormData();
            form.append("rowId", rowId);

            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    alert(request.responseText);
                    // document.getElementById("loadnote").innerHTML = request.responseText;
                    if (request.responseText == "Deleted Succesfully") {
                        location.reload();
                    }
                }

            };
            request.open("POST", "process_deleteNotes.php", true);
            request.send(form);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var submitButtons = document.querySelectorAll(".submitButton");

    submitButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            var assignmentId = button.getAttribute("data-id");
            var inputField = document.getElementById("assAnswer_" + assignmentId);
            var inputValue = inputField.files[0]; // Get the selected file

            console.log("Submit button clicked for assignment with ID: " + assignmentId);
            console.log("Submitted file:", inputValue);

            // alert(assignmentId);
            // alert(id);
            if (inputValue === undefined) {
                alert("Please select file(.pdf)");
            } else {
                var form = new FormData();
                form.append("assAnswer", inputValue);
                form.append("id", assignmentId);

                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        // alert("success"); 
                        alert(request.responseText);

                        if (request.responseText == "Submitted Successfully") {
                            location.reload();
                        }
                    }

                };
                request.open("POST", "process_uploadAss.php", true);
                request.send(form);
            }
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var answerViewButtons = document.querySelectorAll(".answerViewButton");

    answerViewButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            var rowId = button.getAttribute("data-id");

            var form = new FormData();
            form.append("rowId", rowId);

            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    // alert(request.responseText);
                    document.getElementById("container2").innerHTML = request.responseText;

                }

            };
            request.open("POST", "process_loadAllAnswer.php", true);
            request.send(form);
        });
    });
});

function SearchTeacher() {
    var tid = document.getElementById("searchTeacher").value;

    var form = new FormData();
    form.append("tid", tid);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("container").innerHTML = request.responseText;

        }

    };
    request.open("POST", "process_searchTeacher.php", true);
    request.send(form);
}


function TeacherEnrollment() {
    var tid = document.getElementById("searchTeacher").value;
    var groups = Array.from(document.getElementById("groups").selectedOptions).map(opt => opt.value);
    var course = document.getElementById("course").value;

    var form = new FormData();
    form.append("tid", tid);
    groups.forEach(function(val) {
        form.append("groups[]", val);
    });
    form.append("course", course);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);

            if (jsResponseObject.msg.indexOf("Successfully") !== -1) {
                location.reload();
            }
        }
    };
    request.open("POST", "process_addTeacher.php", true);
    request.send(form);
}



function SearchStudent() {
    var sid = document.getElementById("searchStudent").value;

    var form = new FormData();
    form.append("sid", sid);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("container").innerHTML = request.responseText;

        }

    };
    request.open("POST", "process_searchStudent.php", true);
    request.send(form);
}


function ShowAllStudents() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.getElementById("container").innerHTML = request.responseText;
            attachStudentRowEvents();
        }
    };
    request.open("GET", "process_allStudents.php", true);
    request.send();
}
function ShowAllTeachers() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.getElementById("container").innerHTML = request.responseText;
            // Если нужны события для кнопок учителя, добавь здесь свою функцию, например:
            // attachTeacherRowEvents();
        }
    };
    request.open("GET", "process_allTeachers.php", true);
    request.send();
}

function SearchStud() {
    var sid = document.getElementById("searchStud").value.trim();
    if (sid === "") {
        ShowAllStudents();
        return;
    }
    var form = new FormData();
    form.append("sid1", sid);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.getElementById("container").innerHTML = request.responseText;
            attachStudentRowEvents();
        }
    };
    request.open("POST", "process_searchStud.php", true);
    request.send(form);
}

// Attach events to delete/update buttons after table update
function attachStudentRowEvents() {
    document.querySelectorAll(".deleteStudentBtn").forEach(function(btn) {
        btn.onclick = function() {
            if (confirm("Delete this student?")) {
                var username = btn.getAttribute("data-username");
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "process_deleteStudent.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    alert(xhr.responseText);
                    ShowAllStudents();
                };
                xhr.send("username=" + encodeURIComponent(username));
            }
        };
    });

    document.querySelectorAll(".updateStudentBtn").forEach(function(btn) {
        btn.onclick = function() {
            var username = btn.getAttribute("data-username");
            // Здесь можно реализовать модальное окно или редирект на страницу редактирования
            window.location = "UpdateStudent.php?username=" + encodeURIComponent(username);
        };
    });
}

// Показываем всех студентов при загрузке страницы
document.addEventListener("DOMContentLoaded", ShowAllStudents);

function StudentEnrollment() {
    var sid = document.getElementById("searchStud").value;
    var group = document.getElementById("group").value;
    var course_level_1 = document.getElementById("course_level_1").value;
    var courses_level_0 = Array.from(document.getElementById("courses_level_0").selectedOptions).map(opt => opt.value);

    var form = new FormData();
    form.append("sid", sid);
    form.append("group", group);
    form.append("course_level_1", course_level_1);
    // Добавляем до 2 курсов уровня 0
    courses_level_0.slice(0, 2).forEach(function(val) {
        form.append("courses_level_0[]", val);
    });

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);

            if (jsResponseObject.msg == "Succesfully Upgrated Student") {
                location.reload();
            }
        }
    };
    request.open("POST", "process_addStudent.php", true);
    request.send(form);
}

function SearchAccademic() {
    var aid = document.getElementById("searchAccademic").value;

    var form = new FormData();
    form.append("aid", aid);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("container").innerHTML = request.responseText;

        }

    };
    request.open("POST", "process_searchAccademic.php", true);
    request.send(form);
}

function AccademicEnrollment() {
    var aid = document.getElementById("searchAccademic").value;
    var group = document.getElementById("group").value;

    var form = new FormData();
    form.append("aid", aid);
    form.append("group", group);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            alert(jsResponseObject.msg);

            if (jsResponseObject.msg == "Succesfully Added Officer") {
                location.reload();
            }
        }

    };
    request.open("POST", "process_addAccademic.php", true);
    request.send(form);
}

function LoadStudentRequests() {
    var requestId = document.getElementById("requestId").value;

    var form = new FormData();
    form.append("requestId", requestId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);

            // alert(jsResponseObject.username);
            document.getElementById("username").value = jsResponseObject.username;
            document.getElementById("userType").value = jsResponseObject.usertype;
            document.getElementById("fname").value = jsResponseObject.fname;
            document.getElementById("lname").value = jsResponseObject.lname;
            document.getElementById("mobile").value = jsResponseObject.mobile;
            document.getElementById("email").value = jsResponseObject.email;
            document.getElementById("address1").value = jsResponseObject.address1;
            document.getElementById("address2").value = jsResponseObject.address2;
            document.getElementById("city").value = jsResponseObject.city;
            document.getElementById("password").value = jsResponseObject.password;
            document.getElementById("gender").value = jsResponseObject.gender;

        }

    };
    request.open("POST", "process_loadStuRequests.php", true);
    request.send(form);
}

function LoadAssignmentTable() {
    var aId = document.getElementById("assignmentId").value;

    var form = new FormData();
    form.append("aId", aId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("container").innerHTML = request.responseText;

        }

    };
    request.open("POST", "process_loadAllAssAnswer.php", true);
    request.send(form);
}

function AddMarks() {
    var aId = document.getElementById("assignmentId").value;
    var sid = document.getElementById("sid").value;
    var marks = document.getElementById("marks").value;

    var form = new FormData();
    form.append("aId", aId);
    form.append("sid", sid);
    form.append("marks", marks);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("message").innerHTML = request.responseText;
            LoadAssignmentTable();


        }

    };
    request.open("POST", "process_addMarks.php", true);
    request.send(form);
}

function ReleaseMarks() {
    var aId = document.getElementById("assignmentId").value;

    var form = new FormData();
    form.append("aId", aId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // alert(request.responseText);
            document.getElementById("message").innerHTML = request.responseText;
            LoadAssignmentTable();


        }

    };
    request.open("POST", "process_releaseMarks.php", true);
    request.send(form);
}

function VerifyCode() {
    var code = document.getElementById("code").value;

    var form = new FormData();
    form.append("code", code);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            document.getElementById("message").innerHTML = request.responseText;

            var jsonResponseText = request.responseText;
            var jsResponseObject = JSON.parse(jsonResponseText);
            // alert(jsResponseObject.msg);

            try {
                if (jsResponseObject.msg == "Success") {
                    if (jsResponseObject.type == "Admin") {
                        window.location = "AdminDash.php";
                    } else if (jsResponseObject.type == "Teacher") {
                        window.location = "TeacherDash.php";
                    } else if (jsResponseObject.type == "Student") {
                        window.location = "StudentDash.php";
                    } else if (jsResponseObject.type == "Accademic") {
                        window.location = "AccademicDash.php";
                    } else {
                        alert("Please sign in again");
                        window.location = "SignIn.php";
                    }

                } 
            } catch (e) {
                // Response is not valid JSON
                document.getElementById("message").innerHTML = request.responseText;
            }
        }
    };
    request.open("POST", "process_verifyCode.php", true);
    request.send(form);
}


document.addEventListener("DOMContentLoaded", function () {
    var deleteButtons = document.querySelectorAll(".deleteAssignmentButton");
    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            var assignmentId = button.getAttribute("data-id");
            if (confirm("Delete this assignment?")) {
                var form = new FormData();
                form.append("id", assignmentId);
                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        try {
                            var jsResponseObject = JSON.parse(request.responseText);
                            alert(jsResponseObject.msg);
                            if (jsResponseObject.msg === "Assignment deleted!") {
                                location.reload();
                            }
                        } catch (e) {
                            alert(request.responseText);
                        }
                    }
                };
                request.open("POST", "process_deleteAssignment.php", true);
                request.send(form);
            }
        });
    });
});

function LoadAssignmentTable() {
    var assignmentId = document.getElementById("assignmentId").value;
    if (assignmentId == "0") {
        document.getElementById("container").innerHTML = "";
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process_resultView.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        var data = JSON.parse(xhr.responseText);
        var html = "";
        data.forEach(function(row) {
            html += "<tr>";
            html += "<td>" + row.id + "</td>";
            html += "<td>" + row.user_username + "</td>";
            html += "<td>" + row.submitted_at + "</td>";
            html += "<td><a href='" + row.file_path + "'>Download</a></td>";
            html += "<td>" + row.marks + "</td>";
            html += "<td>" + (row.marks >= 40 ? "Pass" : "Fail") + "</td>";
            html += "</tr>";
        });
        document.getElementById("container").innerHTML = html;
    };
    xhr.send("assignment_id=" + encodeURIComponent(assignmentId));
}
$(document).ready(function(){

    $(window).fadeThis();
    
});