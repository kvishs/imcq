<?php 
	ob_start();
	// header ("Content-type: image/jpeg");
 
	//   $file_name = "../assets/images/certificates/certi.jpg";
    //   $string = 'HI THER';
       
       
    //   $im = imagecreatefromjpeg($file_name);
       
    //   $x = 300;//imagesx($im) - $width ;
       
    //   $y = 170; //imagesy($im) - $height;
       
    //  $backgroundColor = imagecolorallocate ($im, 255, 255, 255);
       
    //   $textColor = imagecolorallocate ($im, 0, 0,0);
       
    //   imagestring ($im,$y, $x, $y, $string, $textColor);
     
    //   imagejpeg($im);
    //   imagedestroy($im);


		
?>	<script  type="text/javascript">
		window.location.hash="no-back-button";
		window.location.hash="Again-No-back-button";//again because google chrome don't insert first hash into history
		window.onhashchange=function(){window.location.hash="no-back-button";}
	</script>
	<style type="text/css">
		body{
		    background-image: url("../assets/images/index.jpg");
		    background-size: cover;
		    background-repeat: no-repeat;
		    background-position: center;
		  }
	</style>

<?php include('header.php'); ?>
<?php include('session.php');
	if(isset($_SESSION['exam_run'])){
		header("location:".$_SESSION['exam_run']);
		exiit();
	}
	$right_answer=0;
	$wrong_answer=0;
	$unanswered=0;
	$keys=array_keys($_SESSION['qno']);					
	$order=join(",",$keys);
	$order = substr($order,2);
	$query = "select offeringid, questionanswer from offering where offeringid IN($order) ORDER BY FIELD(offeringid,$order)";
   $response=mysqli_query($con,$query) or die(mysqli_error($con));
   mysqli_query($con,"delete from answers where sid = '".@$_SESSION["exam"]."' and student_id = '".@$_SESSION['pid']."'") or die(mysqli_error($con));
   while($result=mysqli_fetch_array($response)){
	   if($result['questionanswer']==$_SESSION['qno'][$result['offeringid']]){
			   $right_answer++;
		   }else if($_SESSION['qno'][$result['offeringid']]==5){
			   $unanswered++;
			   $_SESSION['qno'][$result['offeringid']] = "No Attempt";
		   }
		   else{
			   $wrong_answer++;
		   }
			$query = "INSERT INTO `answers`(`student_id`, `sid`, `qnumber`, `answer`)
					VALUES ('".@$_SESSION['pid']."','".@$_SESSION["exam"]."','".$result['offeringid']."','".$_SESSION['qno'][$result['offeringid']]."')";
				$members_query = mysqli_query($con,$query)or die(mysqli_error($con));
   }
   $exam = mysqli_query($con,"select * from visitor where sid='".$_SESSION['exam']."'");
   $examdata = mysqli_fetch_assoc($exam);
   $neg_marks = $wrong_answer * $examdata['neg_marks'];
   $right_answer = $right_answer - $neg_marks;
   $right_answer = round($right_answer);
   if ($examdata['display_result'] == 0) {
		result();
		header("location:dashboard");
		exit();
   }
   else
   {
		$row = result();
		$res = mysqli_query($con,"select * from result where sid='".$_SESSION['exam']."' and today='".date("Y-m-d")."' order by scoreobtain desc limit 3");
		$stud = "";
		$marks = "";
		$img = "";
		while ($resultdata = mysqli_fetch_assoc($res)) {
			$student = mysqli_query($con,"select * from teens where keyu='".$resultdata['keyu']."'");
			$studentdata = mysqli_fetch_assoc($student);
			$stud .= $studentdata['fname'].",";
			$marks .= $resultdata['scoreobtain'].",";
			$img .= $studentdata['thumbnail'].",";
		}
		$stud = explode(",",$stud);
		$marks = explode(",",$marks);
		$img = explode(",",$img);
		array_pop($stud);
		array_pop($marks);
		array_pop($img);
		$count_stud = count($stud);
		$count_marks = count($marks);
		$count_img = count($img);
		//unset($stud[2]);
			if ($count_stud == 2 && $count_marks == 2 && $count_img == 2) {
				array_push($stud, 'None');
				array_push($marks, '0');
				array_push($img, '../assets/images/none.png');
			}
			elseif ($count_stud == 1 && $count_marks == 1 && $count_img == 1) {
				array_push($stud, 'None');
				array_push($stud, 'None');
				array_push($marks, '0');
				array_push($marks, '0');
				array_push($img, '../assets/images/none.png');
				array_push($img, '../assets/images/none.png');
			}
			//print_r($img);
?>
<style type="text/css">
	.img1{
		position: absolute;
		margin-top: 141px; 
		margin-left: 104px;
	}
	.img2{
		position: absolute;
		margin-top: 70; 
		margin-left: 298px;
	}
	.img3{
		position: absolute;
		margin-top: 170px; 
		margin-left: 504px;
	}
	.name1{
		position: absolute;
		margin-top: 141px; 
		margin-left: 104px;	
	}
</style>
<div class="container mt-5" style="display: ;">
	<!-- <div class="col-sm-12 col-xs-12">
		<div class="row d-flex justify-content-center">
			<?php
				foreach ($stud as $key => $stud) {
					?>
					<div class="card m-2" style="max-width: 350px;">
					  <div class="row no-gutters">
					    <div class="col-md-4">
					       <img src="<?php echo $img[$key]; ?>" class="card-img" alt="...">
					    </div>
					    <div class="col-md-8">
					      <div class="card-body">
					        <h5 class="card-title"><?php echo $stud; ?></h5>
						        <p class="card-text"><?php echo $marks[$key]." out of ".$row; ?></p>
					      </div>
					    </div>
					  </div>
					</div>
					<?php
				}
			?>
		</div>
	</div> -->
	<div class="d-flex justify-content-center">
		<div class="col-sm-4">
			<div class="card" style="width: 18rem;">
			  <div class="card-header">
			    Your Score
			  </div>
			  <ul class="list-group list-group-flush">
			    <li class="list-group-item">Negative Marks : <span class="answer"><?php echo $neg_marks;?></span></li>
			    <li class="list-group-item">Right answers : <span class="answer"><?php echo $right_answer;?></span></li>
			    <li class="list-group-item">wrong answers : <span class="answer"><?php echo $wrong_answer;?></span></li>
			    <li class="list-group-item">Unanswered Questions : <span class="answer"><?php echo $unanswered;?></span></li>
			  </ul>
			  <div class="card-footer d-flex justify-content-center">
			  	<a href="dashboard" class="btn btn-danger">Finish Exam</a>
			  </div>
			</div>
		</div>	
	</div>
</div>
<?php
	
} 
function result()
{
	global $right_answer,$con,$unanswered,$neg_marks;
	$name = $_SESSION['pid'];						
	$today=date("Y-m-d");						
		$res = mysqli_query($con,"SELECT * FROM `offering` WHERE `sid`='".$_SESSION['exam']."'")or die(mysqli_error($con));
	 	$row = mysqli_num_rows($res);
		if ($right_answer == 0) {
			$pr=0;
		}
		else
		{
			$pr = round(($right_answer*100)/$row,2);	
		}
		$date = date("Y-m-d");
		// $qry = mysqli_query($con,"SELECT passper FROM `visitor` WHERE `sid`='".$_SESSION['exam']."' and startdate='$date'") or die(mysqli_error($con));
		// $pass = mysqli_fetch_assoc($qry);
		if ($pr <= 40.00) {
			$status="Fail";
		}
		else {
			$status="Pass";
		}
		// if($status == 'Pass'){
		// 	include('result_mail.php');
		// }
	mysqli_query($con,"DELETE FROM `result` WHERE keyu='".$_SESSION['pid']."' and sid='".$_SESSION['exam']."'");
	$today = date("Y-m-d");
	$student1 = mysqli_query($con,"select * from teens where keyu='".$_SESSION['pid']."'");
	$studentdata1 = mysqli_fetch_assoc($student1);
	mysqli_query($con,"INSERT INTO `result` (`keyu`, `cid`, `sid`, `did`, `sem_id`, `divi`, `resultstatus`,`pr`, `total_marks`, `scoreobtain`, `un_ans`, `neg_mark`, `status`, `today`) VALUES 
	('".$_SESSION['pid']."','".$studentdata1['cid']."', '".$_SESSION['exam']."','".$studentdata1['did']."', '".$studentdata1['sem_id']."','".$studentdata1['divi']."','$status','$pr', '$row', '$right_answer', '$unanswered', '$neg_marks', 'disable', '$today')");
	$qry = mysqli_query($con,"select * from result where keyu='".$_SESSION['pid']."' and sid='".$_SESSION['exam']."' and today='$today'");
	$data = mysqli_fetch_assoc($qry);
	mysqli_query($con,"UPDATE `answers` SET `rid`='".$data['rid']."' WHERE student_id ='".$_SESSION['pid']."' and sid='".$_SESSION["exam"]."'");
	return $row;
}						
?>
<?php
	@$_SESSION["exam"] = "";
	@$_SESSION["controller"] = "0";
	@$_SESSION["noq"]="0";
	unset($_SESSION['qno']);
	unset($_SESSION['count']);
	unset($_SESSION['que']);
?>
<?php //include('footer.php'); ?>
<?php include('script.php'); 
ob_end_flush();
?>