<?php 
// Assignment id generate process
$group = $_POST["group"];
$course = $_POST["course"];
$year = $_POST["year"];

// validate process
if($group=="0"){
    echo("Select Group");
}else if($course=="0"){
    echo("Select Course");
}else{

$connection = new mysqli("localhost","root","","online_lms");

// count assignments that are release for selected group and course
$table1 = $connection->query("SELECT COUNT(`id`) AS `idcount` FROM `assignment` WHERE 
`group_has_course_group_id`='".$group."' AND `group_has_course_course_id`='".$course."' AND `start_date` LIKE '".$year."%'");

$row = $table1->fetch_assoc();
// then +1 for count
$count = intval($row["idcount"])+1;

// generate id 
echo($year."-".$group."-".$course."-"."AS-".$count);

}

?>