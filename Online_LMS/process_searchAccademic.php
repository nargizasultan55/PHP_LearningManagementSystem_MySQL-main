<?php 
// process search accademic details
    $aid = $_POST["aid"];

            $connection = new mysqli("localhost","root","","online_lms");
            $table = $connection->query("SELECT * FROM `user` INNER JOIN `user_has_group` ON 
            `user`.`username`=`user_has_group`.`user_username` WHERE `user_type_id`='4'");

            if($table->num_rows){
            for ($i=0; $i <$table->num_rows ; $i++) { 
                # code...
                $row = $table->fetch_assoc();

                $table3 = $connection->query("SELECT * FROM `group` WHERE `id`='".$row["group_id"]."'");
                $row3 = $table3->fetch_assoc();
                $group = $row3["name"];

                ?>
                <!-- set officer details to the table -->
                <tr>
                    <th scope="row"><?php echo($i+1)?></th>
                    <td><?php echo($row["username"])?></td>
                    <td><?php echo($row["mobile"])?></td>
                    <td><?php echo($row["email"])?></td>
                    <td><?php echo($group)?></td>
                                        
                </tr>
                <?php
                
                
            }
        }else{
            ?>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                                        
                </tr>
                <?php
        }
        
                
?>