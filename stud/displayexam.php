<?php include("session.php"); ?>

<div class="col-sm-12 col-md-12 col-xs-12 col-lg-12"> 
    <div class="card" id="exam">
        <div class="card-header navbar navbar-inner">
            <header>Available Exams</header>
        </div>
        <div class="card-body">
            <form name="course" id="course" method="post" action="exam.php">        
                <div class="table-responsive col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="datatable">
                        <?php
                        $today = date("Y-m-d");
                        $student_id = $_SESSION['pid'];

                        // Fetch student's semester and enrolled subjects
                        $student_query = mysqli_query($con, "SELECT sem_id FROM teens WHERE keyu = '$student_id'");
                        $student_data = mysqli_fetch_assoc($student_query);
                        $student_semester = $student_data['sem_id'];

                        // Fetch available exams for the student's semester
                        $exam_query = mysqli_query($con, "
                            SELECT v.*, s.subject 
                            FROM visitor v
                            JOIN subject s ON v.sid = s.sid
                            WHERE v.sem_id = '$student_semester' 
                            AND v.startdate >= '$today'
                        ");

                        if (mysqli_num_rows($exam_query) == 0) {
                            ?>
                             <thead>   
                                <tr>
                                    <th>Action</th>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Duration</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>No. of Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="text-center" colspan='7'>  Not Available Exam</td>
                              </tr>
                        </tbody>

                            <?php
                        } else {
                            $i = 1;
                            ?>
                            <thead>   
                                <tr>
                                    <th>Action</th>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Duration</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>No. of Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($exam_query)) {
                                // Fetch the number of questions for each exam
                                $question_query = mysqli_query($con, "SELECT COUNT(*) as total_questions FROM offering WHERE sid = '".$row['sid']."'");
                                $question_data = mysqli_fetch_assoc($question_query);
                                $total_q = $question_data['total_questions'];

                                // Check if the student has already completed the exam
                                $result_query = mysqli_query($con, "
                                    SELECT * FROM result 
                                    WHERE keyu = '$student_id' 
                                    AND today = '$today' 
                                    AND sid = '".$row['sid']."'
                                ");
                                $result_count = mysqli_num_rows($result_query);

                                if ($result_count == 1 || $row['examstatus'] == 'Complete') {
                                    echo "<tr><td class='alert col-sm-12 text-center' colspan='7'>You have completed this exam.</td></tr>";
                                } else {
                                    ?>
                                    <tr>
                                        <td><a href="studrules.php?exam=<?php echo $row['sid']; ?>" class="btn btn-info" data-placement="right" title="Start Exam"><i class="icon-edit icon-large"></i> Start Exam</a></td>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['subject']; ?></td>
                                        <td><?php echo $row['duration']." Minute"; ?></td>
                                        <td><?php echo $row['startdate']; ?></td>
                                        <td><?php echo $row['starttime']; ?></td>
                                        <td><?php echo $total_q; ?></td>
                                    </tr>
                                    <?php
                                }
                                $i++;
                            }
                            ?>
                            </tbody>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /block --> 
