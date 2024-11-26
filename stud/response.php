<?php
include('dbconn.php');
	session_start();
	if (isset($_POST['display'])) {
		$members_query = mysqli_query($con,"SELECT * FROM `visitor` where `sid`='".$_SESSION['exam']."'")or die(mysqli_error($con));
	    $row = mysqli_fetch_array($members_query);
		if ($row['time_base'] == '1') {
			if( @$_SESSION["controller"] != "" ){
				if( @$_SESSION["controller"] == "1" ){
					$from_time = date("Y-m-d H:i:s");
					$to_time = @$_SESSION["end_time"];

					if( $to_time != "" ){
						$start_at = strtotime($from_time);
						$seconds = strtotime($to_time);
						$diffrence = $seconds - $start_at;
						if(gmdate("H:i:s",$diffrence) == "00:00:00"){
							$duration = $row['time_on_que'];
			                echo $duration;
			                @$_SESSION["controller"] = "1";
			          //  }
			                @$_SESSION["timer"] = $duration;
			                $_SESSION["start_time"] = date("Y-m-d H:i:s");
			                @$end_time = date("Y-m-d H:i:s", strtotime('+'.$_SESSION["timer"].'seconds', strtotime($_SESSION["start_time"])));
			                if( $end_time != ""){
			                    $_SESSION["end_time"] = @$end_time;
			                   // $arr = urlencode(serialize($qno));
			                }
						}
						else{
			                echo gmdate("H:i:s",$diffrence);
							
			            }
					}
				}else{
					echo "TIME IS UP";
				}
			}
		}
		else{
			if( @$_SESSION["controller"] != "" ){
				$from_time = time(); // Get the current time in seconds since the Unix Epoch
            $duration = (int)$row['duration']; // Assuming duration is in seconds
            $to_time = @$_SESSION["end_time"];
            
            if ($to_time != "") {
                $end_time = strtotime($to_time); // Convert end_time to a timestamp

                // Calculate the difference in seconds
                $difference = $end_time - $from_time; // Calculate remaining time
                
                // Check if time is up
                if ($difference <= 0) {
                    echo "TIME IS UP";
                    $_SESSION["controller"] = "0"; // Stop the timer
                } else {
                    // Calculate minutes and seconds
                    $minutes = floor($difference / 60);
                    $seconds = $difference % 60;

                    // Format to mm:ss
                    echo sprintf("%02d:%02d", $minutes, $seconds);
                }
			}
		}
	}
}
	
?>