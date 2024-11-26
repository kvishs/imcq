<?php
include('session.php');
if (isset($_POST['display'])) {
	?>
	
    <form action=" " method="post">
        <?php
        if ($_SESSION['type'] != 3) {
            ?>
            <a data-placement="right" title="Click to Delete check item" href="#" id="delete"  class="btn btn-danger mb-2" name="" onClick="return chck()"><i class="fas fa-trash-alt"> Delete</i></a>
            <?php
        }
        ?>
        <?php include('modal_delete.php'); ?>
        <div class="table-responsive col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: ;">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="datatable">

                <thead>
                    <tr>
                        <th><input type="checkbox" onClick="toggle(this)" onClick="toggle()" id="checkUncheckAll"/><br/></th>
                                            <th>Class</th>
                                            <th>Semester</th>
											<th>Subject</th>
											<th>division</th>
											<th>Exam Date</th>
											<th>Exam Tike</th>
											<th>Duration</th>
											<th>status</th>
                        <?php
                        if ($_SESSION['type'] != 3) {
                            ?><th></th>
                            <th></th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-----------------------------------Content------------------------------------>
                        <?php
                        if ($_SESSION['who'] == 'admin' || $_SESSION['type'] == 3) {
                            $members_query = mysqli_query($con,"select * from visitor")or die(mysqli_error($con));
                        }
                        elseif($_SESSION['who'] == 'fact'){
                            $sub = mysqli_query($con,"select sid from fact where fid='$session_id'")or die(mysqli_error($con));
                            $subdata = mysqli_fetch_assoc($sub);
                            if (trim($subdata['sid']) == "") {
                                ?><script type="text/javascript">
                                    $.alert({
                                        columnClass: 'medium',
                                        title: 'Information',
                                        content: 'You are not taking any Subject!',
                                        type: 'purple',
                                        typeAnimated: true,
                                        buttons: {
                                            Ok: function(){
                                                location.href = "dashboard";
                                            }
                                        }
                                    });
                                </script>
                                <?php
                                exit();
                            }
                            else
                            {
                                if ($_SESSION['type'] == 2) {
                                    $members_query = mysqli_query($con,"select * from visitor where `sid` IN(".trim($subdata['sid']).")")or die(mysqli_error($con));      
                                }
                                elseif ($_SESSION['type'] == 1) {
                                    $members_query = mysqli_query($con,"select * from visitor where `sid` IN(".trim($subdata['sid']).") or did='".$_SESSION['co_dept']."'")or die(mysqli_error($con));       
                                }
                            }
                        }

                        while($row = mysqli_fetch_array($members_query)){
                            $id = $row['id'];	
                            $dept = mysqli_query($con,"select * from class where id='".$row['did']."'")or die(mysqli_error($con));
                            $deptdata = mysqli_fetch_assoc($dept);
                            $qsu = @mysqli_query($con,"select subject from subject where sid = '".$row['sid']."'")or die(mysqli_error($con));
                            $sub = mysqli_fetch_array($qsu);
                            $qsu1 = @mysqli_query($con,"select sem_name from sem where sem_id = '".$row['sem_id']."'")or die(mysqli_error($con));
                            $sub1 = mysqli_fetch_array($qsu1);
                            ?>												
                            <tr>
                                <td><input id="optionsCheckbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                                <td><?php echo $deptdata['dept'];?></td>
                                                <td><?php echo $sub1['sem_name'];?></td>
												<td><?php echo $sub['subject']; ?> </td>
												<td><?php echo $row['divi']; ?></td>
												<td><?php echo $row['startdate']; ?></td>
												<td><?php echo $row['starttime']; ?></td>
												<td><?php echo $row['duration']; ?></td>
                                               

                                                   <?php
                                if ($_SESSION['type'] != 3) {
                                    ?>
                                    <?php //include('toolttip_edit_delete.php'); ?>
                                    <td><a name="status" title="click to Complated" id="<?php echo $id; ?>_status" class="btn btn-success text-white" onclick='status(<?php echo $id; ?>)'><?php echo $row['examstatus'] ?></a></td>

                                    <!-- <td><a rel="tooltip" title="Edit Exam" id="e<?php echo $id; ?>" href="#" onclick="editexam(<?php echo $id; ?>)"><i class="fas fa-fw fa-pencil-alt"></i></a></td> -->
                                    <td><a rel="tooltip" title="Delete Student" id="e<?php echo $id; ?>" href="javascript:delete_id(<?php echo $id; ?>)" name="del"><i class="fas fa-fw fa-trash"></i></a></td>
                                    <?php
                                }
                                ?>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <?php
}

if (isset($_POST['update_status']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Fetch current status
    $query = mysqli_query($con, "SELECT examstatus FROM visitor WHERE id = $id") or die(mysqli_error($con));
    $row = mysqli_fetch_assoc($query);
    $current_status = $row['examstatus'];

    // Only change the status if it's "Running"
    if ($current_status == "Running") {
        $new_status = "Complete";
        
        // Update status in the database
        mysqli_query($con, "UPDATE visitor SET examstatus = '$new_status' WHERE id = $id") or die(mysqli_error($con));

        // Log activity (optional)
        mysqli_query($con, "INSERT INTO activity_log (date, username, action) VALUES (NOW(), '$session_username', 'Updated Exam Status for Exam ID: $id')") or die(mysqli_error($con));

        // Send the new status back to the client
        echo $new_status;
    } else {
        echo $current_status; // If already completed or other, return the current status
    }
    exit();
}
?>