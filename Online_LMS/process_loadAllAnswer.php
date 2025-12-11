
<?php
// Load answer process
$phpResponseObject = new stdClass();
$id = $_POST["rowId"];

$connection = new mysqli("localhost", "root", "", "online_lms");

// search student with release assignment
$table = $connection->query("SELECT * FROM `user_has_release_assignment` WHERE `assignment_id`='" . $id . "'");

for ($i=0; $i < $table->num_rows; $i++) { 
    $row = $table->fetch_assoc();
    ?>
    <!-- table load process -->
    <tr>
        <th scope="row"><?php echo($row["id"]) ?></th>
        <td><?php echo($row["user_username"]) ?></td>
        <td><?php echo($row["submitted_at"]) ?></td>
        <?php 
        if(empty($row["file_path"]) || $row["file_path"]=="null"){
            // if assignment not submitted
            ?>
                <td><label class="text-danger">Not submitted</label></td>
            <?php
        }else{
            ?>
                <td><a href="<?php echo($row["file_path"]) ?>" class="btn btn-success">View</a></td>
            <?php
        }
        ?>
        <td>
            <form method="post" action="process_addMarks.php" style="display:inline-flex;">
                <input type="hidden" name="aId" value="<?php echo($row["assignment_id"]) ?>">
                <input type="hidden" name="sid" value="<?php echo($row["user_username"]) ?>">
                <input type="number" name="marks" min="0" max="100" value="<?php echo($row["marks"]) ?>" required style="width:70px;">
                <button type="submit" class="btn btn-primary btn-sm">Set Mark</button>
            </form>
        </td>
    </tr>
    <?php 
}
?>