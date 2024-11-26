<?php 
	session_start();
	if (isset($_SESSION['pid'])) 
	{
		header("location:dashboard");
	}
	include('header.php');
    include("dbconn.php");
?>	
<?php
if (isset($_POST['login']))
{
	$error ="";
	$enrollno=$_POST['enroll'];
	$pass=md5($_POST['pass']);
	$login_query=mysqli_query($con,"select * from teens where enroll='$enrollno' and password='$pass'");
	$count=mysqli_num_rows($login_query);
	$row=mysqli_fetch_array($login_query);

	// echo $row;
	if ($count > 0){
		//session_start();
		$_SESSION['pid']=$row['keyu'];
		$oras = strtotime("now");
		$ora = date("Y-m-d",$oras);
		$user = mysqli_query($con,"select * from active_users where keyu='".$row['keyu']."' ORDER BY id desc");
		while($userdata = mysqli_fetch_assoc($user)){
			$_SESSION['id'] = $userdata['id'];
			break;
		}
		?>
		<script type="text/javascript">
				$.alert({
				columnClass: 'medium',
		        title: 'Congratulation',
		        content: 'Login Successfull!',
		        type: 'green',
		        typeAnimated: true,
		            buttons: {
				        Ok : function(){
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
		?>
			<script type="text/javascript">
				$.alert({
					columnClass: 'medium',
		        title: 'Report To Your Admin!',
		        content: 'You Are Not Register or Deactivate By Admin',
		        type: 'red',
		        typeAnimated: true,
		        buttons: {
		            Ok: {
		                text: 'Ok',
		                btnClass: 'btn-red',
		            }
		        }
		    });
			</script>
		<?php
		$error ="Invalid User";
		//header('location:./');
	}
}
?>	

<style type="text/css">
  html{
    height: 100%;
  }
	
  #page-content {
	flex: 1 0 auto;
  }

  #sticky-footer {
	flex-shrink: none;
  }

  body{
    background: url("../assets/images/index.jpg")no-repeat center center fixed;
	-webkit-background-size: 100%;
	-moz-background-size: cover;
	-o-background-size: cover;
    background-size: cover;	
  }
	.user_card {
		height: 425px;
		width: 350px;
		margin-top: 100px;
		margin-bottom: auto;
		display: flex;
		justify-content: center;
		flex-direction: column;
		padding: 10px;
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		border-radius: 5px;

	}
	.brand_logo_container {
		margin-top: 170px;
		position: absolute;
		height: 220px;
		width: 220px;
		top: -75px;
		border-radius: 50%;			
		padding: 10px;
		text-align: center;
	}
	.brand_logo {
		height: 150px;
		width: 160px;
		border-radius: 50%;
		
	}
	.form_container {
		margin-top: 170px;
	}
	.login_btn {
		width: 100%;
		background: #f18036 !important;
		color: white !important;
	}
	.login_btn:focus {
		box-shadow: none !important;
		outline: 0px !important;
	}
	.login_container {
		padding: 0 2rem;
	}
	.input-group-text {
		background: #f18036 !important;
		color: white !important;
		border: 0 !important;
		border-radius: 0.25rem 0 0 0.25rem !important;
	}
	.input_user,
	.input_pass:focus {
		box-shadow: none !important;
		outline: 0px !important;
	}
	.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
		background-color: #f18036 !important;
	}
	.hcolor{
		color: #011378;
		
	}
</style>	
<body class="d-flex flex-column">
  <div class="container" id="page-content">
    <!-- Outer Row -->
	<div class="justify-content-center">
			<div class="user_card container">
			<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="../assets/images/booklogo.png" class="brand_logo" alt="Logo">
					</div>
				</div>	
				<div class="d-flex justify-content-center form_container">
				<div class="p-1">
				
					<div class="text-center">
						<h4 class=" hcolor mb-2">Online Examination System</h1>						
						<h1 class="h5 text-white mb-2">Student Login</h1>
					</div>
					<form class="user" action=" " method="post" onSubmit="$.jGrowl('Default Positioning');">
					<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="enroll" class="form-control input_user" value="" placeholder="Enroll No" required>
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input id="password-field" type="password" name="pass" class="form-control input_pass" value="" placeholder="Password" required>
							<div class="input-group-append">
								<span class="input-group-text" style="border-radius: 0 0.25rem 0.25rem 0!important;"><i toggle="#password-field" class="fas fa-lg fa-eye toggle-password" ></i></span>
							</div>	
</div>
<div class="d-flex justify-content-center my-3 login_container">
							<input type="submit" name="login" value="Login" class="btn login_btn" title="Click Here to Sign In" id="login" data-toggle="tooltip" data-placement="right">
						</div>			<div class=" text-center">				
							<a target="_blank" href="https://amrolicollege.ac.in/" class="bottom-text-w3ls h5 text-white mb-4">Visit Our College</a>
						</div>
					</form>
					</div>
				</div>					
			</div>
		</div>       
  </div>
  <!-- Footer -->
	<footer id="sticky-footer" class="py-4 font-small blue text-white-50">
	  <!-- Copyright -->
	  <div class="container text-center">&copy; I-MCQ<?php $date = new DateTime();echo $date->format(' Y');?></div>
	  <!-- Copyright -->
	</footer>
  <!-- Footer -->	
  
</body>
<script>
$(".toggle-password").click(function() {
  $(this).toggleClass("fa-unlock");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

