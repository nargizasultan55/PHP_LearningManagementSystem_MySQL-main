<?php 
// generate note id

$group = $_POST["group"];
$course = $_POST["course"];
$year = date("Y");

// validate process
if($group=="0"){
    echo("Select Group");
}else if($course=="0"){
    echo("Select Course");
}else{

$connection = new mysqli("localhost","root","","online_lms");

// get count of have notes selected  group and course
$table1 = $connection->query("SELECT COUNT(`id`) AS `idcount` FROM `notes` WHERE 
`group_has_course_group_id`='".$group."' AND `group_has_course_course_id`='".$course."'");

$row = $table1->fetch_assoc();
// set +1 for count
$count = intval($row["idcount"])+1;

// generate id
echo($year."-".$group."-".$course."-"."NOTE-".$count);

}

?>