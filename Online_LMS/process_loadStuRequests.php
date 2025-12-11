
<?php 
$requestId = $_POST["requestId"];
$milliseconds = date_create()->format('Uv');
$phpResponseObject = new stdClass();

$connection = new mysqli("localhost","root","","online_lms");
$table = $connection->query("SELECT * FROM `request` WHERE `id`='".$requestId."' AND `user_type_id`='3'");
$row = $table->fetch_assoc();

if($requestId!="0" && $row){
    $phpResponseObject->username = $row["email"];
    $phpResponseObject->usertype = $row["user_type_id"];
    $phpResponseObject->fname = $row["first_name"];
    $phpResponseObject->lname = $row["last_name"];
    $phpResponseObject->mobile = $row["mobile"];
    $phpResponseObject->email = $row["email"];
    $phpResponseObject->address1 = $row["address_1"];
    $phpResponseObject->address2 = $row["address_2"];
    $phpResponseObject->city = $row["city_id"];
    $phpResponseObject->password = $milliseconds;
    $phpResponseObject->gender = $row["gender_id"];
}else{
    $phpResponseObject->username = "";
    $phpResponseObject->usertype = "0";
    $phpResponseObject->fname = "";
    $phpResponseObject->lname = "";
    $phpResponseObject->mobile = "";
    $phpResponseObject->email = "";
    $phpResponseObject->address1 = "";
    $phpResponseObject->address2 = "";
    $phpResponseObject->city = "0";
    $phpResponseObject->password = "";
    $phpResponseObject->gender = "0";
}

echo json_encode($phpResponseObject);
?>