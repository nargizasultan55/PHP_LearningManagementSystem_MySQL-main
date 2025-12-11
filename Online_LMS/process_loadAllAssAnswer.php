<?php
// Process load assignment answers

$id = $_POST["aId"];

$connection = new mysqli("localhost", "root", "", "online_lms");
$table = $connection->query("SELECT * FROM `user_has_release_assignment` WHERE `assignment_id`='" . $id . "'");
// search student release assignment

// array for store status
$status = ["Not Submit", "Submitted", "Marking Assigned", "Released"];

if($table->num_rows){
for ($i=0; $i < $table->num_rows; $i++) { 
    # code...
    $row = $table->fetch_assoc();
    ?>
    <!-- load table data -->
    <tr>
        <th scope="row"><?php echo($row["id"])?></th>
        <td><?php echo($row["user_username"])?></td>
        <td><?php echo($row["submitted_at"])?></td>
        <?php 
        // if check assignment submitted or not
        if($row["file_path"]=="null"){
            ?>
                <td><label class="text-danger">Not submitted</label></td>
            <?php
        }else{
            ?>
            <!-- view assignment -->
                <td><a href="<?php echo($row["file_path"])?>" class="btn btn-success">View</a></td>
            <?php
        }
        ?>
        <td><label><?php echo($row["marks"])?></label></td>

        <!-- get marks status id from array -->
        <td><label class="text-primary"><?php echo($status[$row["mark_status_id"]-1])?></label></td>

    </tr>
    
    <?php 

}
}else{
    // if no answers for assignment
    echo("No Assignment Answers");
}


?>