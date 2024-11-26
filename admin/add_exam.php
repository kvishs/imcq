<?php
	ob_start();
	include("header.php");
	include("session.php");
	include("sidebar.php");
	include("navbar.php");
if ($_SESSION['type'] == 3) {
        header("location:404.html");
        exit();
    }
    if($_SESSION['type'] == '2'){
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
    }
?>
    <div class="container-fluid fa-sm">
        <div class="row">
            <div class="col-sm-4" id="addmembers">
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon-info-sign"></i>  <strong>Note!:</strong>Add Exam 
				</div>
                <?php include('form_add_exam.php'); ?>
            </div>
            <div class="col-sm-8" id="">
                <div class="row">
                    <!-- block -->

                    <?php
                    $count_members=mysqli_query($con,"select * from visitor")or die(mysqli_error($con));
                    $count = mysqli_num_rows($count_members);
                    ?>
					<div class="col-sm-12">                              
                    <div class="card shadow fa-sm">                        
                        <div class="navbar navbar-inner card-header">
                            <div class="muted pull-right">
                                Number of Exam: <span class="badge badge-info"><?php  echo $count; ?></span>
                            </div>
                            <div class="tools">
	                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
	                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
	                        </div>
                        </div>
                        <div class="card-body">
                            <div class="col-dm-12">                              
								<div class="table-responsive col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top:;">
								<table cellpadding="0" cellspacing="0" border="0" class="table" id="dataTable">
									<thead>
										<tr>
											<th>Class</th>
                                            <th>Semester</th>
											<th>Subject</th>
											<th>division</th>
											<th>Exam Date</th>
											<th>Exam Tike</th>
											<th>Duration</th>
										</tr>
									</thead>
									<tbody>
										<?php
										 if ($_SESSION['who'] == 'admin') {
                                                $members_query = mysqli_query($con,"select * from visitor")or die(mysqli_error($con));
                                            }
                                            elseif($_SESSION['who'] == 'fact'){
                                                $sub = mysqli_query($con,"select sid from fact where fid='$session_id'")or die(mysqli_error($con));
                                                $subdata = mysqli_fetch_assoc($sub);
                                                if (trim($subdata['sid']) == "") {
                                                    $members_query = mysqli_query($con,"select * from visitor where id is null")or die(mysqli_error($con));
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
                                            $sub = @mysqli_query($con,"select subject from subject where sid = '".$row['sid']."'")or die(mysqli_error($con));
                                            $subdata = mysqli_fetch_array($sub);
											?>

											<tr>
												<!-- edit their values -->
												<td><?php echo $deptdata['dept'];?></td>
                                                <td><?php echo $row['divi'];?></td>
												<td><?php echo $subdata['subject']; ?> </td>
												<td><?php echo $row['divi']; ?></td>
												<td><?php echo $row['startdate']; ?></td>
												<td><?php echo $row['starttime']; ?></td>
												<td><?php echo $row['duration']; ?></td>
												</tr>
											<?php } ?>
									</tbody>
								</table>
								</div>
							</div>
                        </div>
                    </div>
                        <!-- /block -->
                </div>
        </div>
    </div>
</div>
</div>
</div>
<?php include('footer.php'); ?>
<?php include('script.php'); 
ob_end_flush();
?>
