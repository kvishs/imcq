<?php
	ob_start();
	include("header.php");
	include("session.php");
	include("sidebar.php");
	include("navbar.php");
	if (isset($_GET['id'])) {
		$id=$_GET['id'];
	}
	else
	{
		header("location:displayexam");
		exit();
	}
?>

					<?php
						$exam = mysqli_query($con,"select * from visitor where sid=$id");
						$examdata = mysqli_fetch_assoc($exam);
						$stud = mysqli_query($con,"SELECT * FROM `result` WHERE keyu='".$_SESSION['pid']."' and sid='".$examdata['sid']."'");
						
						if ($row = mysqli_num_rows($stud) == 0) {
							?>
							<script type="text/javascript">
								$.alert({
									columnClass: 'medium',
						        title: 'Alert!',
						        content: 'Complete This Exam First!',
						        type: 'red',
						        typeAnimated: true,
						        buttons: {
						            Ok: function() {
						                location.href = "result_stud";
						            }
						        }
						    });
							</script>
						<?php
							exit();
						}
						else{
						$studdata = mysqli_fetch_assoc($stud);
						$sub_query = mysqli_query($con, "
						SELECT v.*, s.subject 
						FROM visitor v
						JOIN subject s ON v.sid = s.sid
						WHERE v.id = '".$examdata['id']."'");
						$subdata = mysqli_fetch_assoc($sub_query);
							?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card shadow fa-sm">
				<div class="card-header navbar navbar-inner">
					<header class="h5 text-left">Check your answers</header>
					<div class="tools">
						<a href="result_stud">Back</a>
						<a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>	                   
	                </div>
				</div>
				<div class="card-body">
					<table class="table table-striped table-bordered table-hover table-checkable order-column">
						<tr class="text-white bg-info" style="background-color: ;">
							<th>Exam Name</th>
							<th>Your Score</th>
							<th>Incorrect</th>
							<th>Unanswered</th>
							<th>Negative Marks</th>							
						</tr>
						<tr>
							<td><?php echo  $subdata['subject']; ?></td>
							<td><?php echo $studdata['scoreobtain']; ?></td>
							<td><?php echo $studdata['total_marks'] - $studdata['scoreobtain'] - $studdata['un_ans']; ?></td>
							<td><?php echo $studdata['un_ans']; ?></td>
							<td><?php echo $studdata['neg_mark']; ?></td>

						</tr>
					</table>
					<?php
							$quetion = mysqli_query($con,"select * from offering where sid='".$examdata['sid']."'");
								$i = 1;
								while ($quetiondata = mysqli_fetch_assoc($quetion)) {
									?><hr>
									<div id="<?php echo "que".$quetiondata['offeringid']; ?>">
										<div class="p-4">
									<p><?php echo $i.". ". $quetiondata['questiondesc']; ?>   <i class="fas fa-fw"></i></p>
									A <input type="radio" name="radio1<?php echo $quetiondata['offeringid']; ?>" value="A" disabled><?php echo " ".htmlspecialchars($quetiondata['valueoptions']);?><br>
									B <input type="radio" name="radio1<?php echo $quetiondata['offeringid']; ?>" value="B" disabled><?php echo " ".htmlspecialchars($quetiondata['valueoptionsb']);?><br>
									C <input type="radio" name="radio1<?php echo $quetiondata['offeringid']; ?>" value="C" disabled><?php echo " ".htmlspecialchars($quetiondata['valueoptionsc']);?><br>
									D <input type="radio" name="radio1<?php echo $quetiondata['offeringid']; ?>" value="D" disabled><?php echo " ".htmlspecialchars($quetiondata['valueoptionsd']);?><br><br>
									<script type="text/javascript">
						                $(document).ready(function(){
						                    ($("input[name=radio1<?php echo $quetiondata['offeringid']; ?>][value='<?php echo $quetiondata['questionanswer'] ?>']").prop("checked",true))
						                })
						            </script>
						            
									<?php
									$ans = mysqli_query($con,"select * from answers where qnumber='".$quetiondata['offeringid']."' and student_id='".$_SESSION['pid']."'");
									$ansdata = mysqli_fetch_assoc($ans);
									?>
									<span id="ans<?php echo "que".$quetiondata['offeringid']; ?>">Your Answer is :<?php echo $ansdata['answer']; ?></span></div></div>
									<?php
									if ($ansdata['answer'] == $quetiondata['questionanswer']) {
										?>
										<script type="text/javascript">
											$(document).ready(function(){
												$("#<?php echo 'que'.$quetiondata['offeringid']; ?>").addClass("alert-success ");
												//$("#ans<?php echo 'que'.$quetiondata['offeringid']; ?>").addClass("alert alert-success");
											})
										</script>
										<?php
									}
									else
									{
										?>
										<script type="text/javascript">
											$(document).ready(function(){
												$("#<?php echo 'que'.$quetiondata['offeringid']; ?>").addClass("alert-danger");
												//$("#ans<?php echo 'que'.$quetiondata['offeringid']; ?>").addClass("alert alert-danger");
											})
										</script>
										<?php
									}
									$i++;
								}?>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php
						}
					?>
<?php include('footer.php'); ?>
<?php include('script.php'); 
ob_end_flush();
?>
