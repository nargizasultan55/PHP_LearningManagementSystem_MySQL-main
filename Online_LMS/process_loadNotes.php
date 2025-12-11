<?php 
// process load notes process
session_start();
$course = $_POST["course"];

$connection = new mysqli("localhost","root","","online_lms");

// search student group
$table3 = $connection->query("SELECT * FROM `user_has_group` WHERE `user_username`='".$_SESSION["u"]."'");

$row3 = $table3->fetch_assoc();
if($row3){
    // search notes for selected course and group he is
    $table4 = $connection->query("SELECT * FROM `notes` WHERE `group_has_course_group_id`='".$row3["group_id"]."' 
    AND `group_has_course_course_id`='".$course."'");
    if($table4->num_rows){
        // if notes have
        for ($i=0; $i <$table4->num_rows ; $i++) { 
            # code...
            $row4 = $table4->fetch_assoc();
            ?>
<!-- note view as cards -->
                <div class="col-sm-6 mt-3">
                    <div class="card">
                        <div class="card-body bg-primary bg-opacity-25">
                            <div class="bg-primary bg-opacity-50 p-1 mb-1">
                                <label class="card-title bg-opacity-50 fs-6"><?php echo($row4["id"])?></label>
                            </div>
                            
                            <p class="card-text fs-4"><?php echo($row4["name"])?></p>
                            <!-- note download option -->
                            <a href="<?php echo($row4["note_location"])?>" class="btn btn-primary">Download</a>
                        </div>
                    </div>
                </div>

            <?php 
        }
    }else{
        // if no lesson notes
        echo("No Lesson Notes");
    }

}

?>
