<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>IIITN grading</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="content-wrapper">
                <div class="content-container">

         
                    <!-- /.left-sidebar -->

                    <div class="main-page" style="background-color:#d9e6f7">
                        <div class="container-fluid">
                            <div class="row page-title-div" style="background-color:#89a9d2; ">
                                <div class="col-md-12">
                                    <h2 class="title" align="center">IIITN grading system</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                          
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <!-- <section class="section"> -->
                            <div class="container-fluid">

                                <div class="row" style="background-color:#d9e6f7;padding:30px">
                              
                             

                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
<?php

$rollid=$_POST['rollid'];
$classid=$_POST['class'];
$_SESSION['rollid']=$rollid;
$_SESSION['classid']=$classid;
$qery = "SELECT   tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=:rollid and tblstudents.ClassId=:classid ";
$stmt = $dbh->prepare($qery);
$stmt->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$stmt->bindParam(':classid',$classid,PDO::PARAM_STR);
$stmt->execute();
$resultss=$stmt->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($stmt->rowCount() > 0)
{
foreach($resultss as $row)
{   ?>
<p><b>Student Name :</b> <?php echo htmlentities($row->StudentName);?></p>
<p><b>Enrollment no. :</b> <?php echo htmlentities($row->RollId);?>
<p><b>Class:</b> <?php echo htmlentities($row->ClassName);?>(<?php echo htmlentities($row->Section);?>)
<?php }

    ?>
                                            </div>
                                            <div class="panel-body p-20">







                                                <table class="table table-hover table-bordered">
                                                <thead>
                                                        <tr>
                                                            <th>sr. no.</th>
                                                            <th>Course</th>    
                                                            <th>Marks</th>
                                                            <th>Grade</th>
                                                        </tr>
                                               </thead>
  


                                                	
                                                	<tbody>
<?php                                              
// Code for result

 $query ="select t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=:rollid and t.ClassId=:classid)";
 
 
 $query= $dbh -> prepare($query);
 $query->bindParam(':rollid',$rollid,PDO::PARAM_STR);
 $query->bindParam(':classid',$classid,PDO::PARAM_STR);
 $query-> execute(); 
 $results = $query -> fetchAll(PDO::FETCH_OBJ);

$query1 ="select t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.ClassId=:classid)";
$query1= $dbh -> prepare($query1);
$query1->bindParam(':classid',$classid,PDO::PARAM_STR);
$query1-> execute(); 
$results1 = $query1 -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
// $i; 
// $j; 
// $size = count($results1);
// $average = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

// $numberofstudents = $size/count($results1);

// for($i = 0; $i < $size; $i++) 
//     if( $average[$results1[$i]->SubjectId] == 0)
//         $average[$results1[$i]->SubjectId] =  $results1[$i]->marks;
//     for($j = $i + 1; $j < $size; $j++) 
//         if($results1[$i]->SubjectId == $results1[$j]->SubjectId) {
//             $average[$results1[$i]->SubjectId] = $average[$results1[$i]->SubjectId] + $results1[$j]->marks;
//             echo($average[$results1[$j]->SubjectId]);
//             array_splice($results1, $j, $j);
//         }


if($countrow=$query->rowCount()>0)
{ 
foreach($results as $result){
    $query2 = "select marks from tblresult where SubjectId = '".$result->SubjectId."'";
    $query2= $dbh -> prepare($query2);
    $query2-> execute(); 
    $results2 = $query2 -> fetchAll(PDO::FETCH_OBJ);

    $standardDeviation;
    $average;

    $num_of_elements = count($results2);
          
        $variance = 0.0;
          
                // calculating mean using array_sum() method
        $sumofelements=0;
        foreach($results2 as $res){
            $sumofelements+=$res->marks;
        }
        $average = $sumofelements/$num_of_elements;
          
        foreach($results2 as $i)
        {
            // sum of squares of differences between 
                        // all numbers and means.
                        $variance += pow(($i->marks - $average), 2);
        }
          
        $standardDeviation = (float)sqrt($variance/$num_of_elements);
        // echo($standardDeviation);
        ?><br>
        <?php
        // echo($average);
    ?>

                                                		<tr>
                                                <th scope="row"><?php echo htmlentities($cnt);?></th>
                                                			<td><?php echo htmlentities($result->SubjectName);?></td>
                                                			<td><?php echo htmlentities($totalmarks=$result->marks);?></td>
                                                            <td><?php 
                                                            if($result->marks >= $average +(1)*$standardDeviation){
                                                                echo htmlentities("AA");
                                                            }
                                                            else if($average+(1)*$standardDeviation > $result->marks && $result->marks >= $average+(0.5)*$standardDeviation){
                                                                echo htmlentities("AB");
                                                            }
                                                            else if($average+(0.5)*$standardDeviation > $result->marks&& $result->marks >= $average){
                                                                echo htmlentities("BB");
                                                            }
                                                            else if($average> $result->marks && $result->marks >= $average - (0.5)*$standardDeviation){
                                                                echo htmlentities("BC");
                                                            }
                                                            else if($average - (0.5)*$standardDeviation > $result->marks && $result->marks >= $average-(0.75)*$standardDeviation){
                                                                echo htmlentities("CC");
                                                            }
                                                            else if($average - (0.75)*$standardDeviation > $result->marks && $result->marks >= $average-$standardDeviation){
                                                                echo htmlentities("DD");
                                                            }
                                                            else{
                                                                echo htmlentities("FF");
                                                            }
                                                             ?></td>
                                                            
                                                		</tr>
<?php 
$totlcount+=$totalmarks;
$cnt++;}
?>

<tr>
                                                <th scope="row" colspan="2">Total Marks</th>
<td><b><?php echo htmlentities($totlcount); ?></b> out of <b><?php echo htmlentities($outof=($cnt-1)*100); ?></b></td>
                                                        </tr>
<tr>
                                                <th scope="row" colspan="2">Percentage</th>           
                                                            <td><b><?php echo  htmlentities($totlcount*(100)/$outof); ?> %</b></td>
                                                             </tr>
<tr>
                                                <!-- <th scope="row" colspan="2">Download Result</th>           
                                                            <td><b><a href="download-result.php">Download </a> </b></td>
                                                             </tr> -->

 <?php } else { ?>     
<div class="alert alert-warning left-icon-alert" role="alert">
                                            <strong>Important note:</strong> Your result is not declared yet!!
 <?php }
?>
                                        </div>
 <?php 
 } else
 {?>

<div class="alert alert-danger left-icon-alert" role="alert">
strong>Oh snap!</strong>
<?php
echo htmlentities("Invalid Roll Id");
 }
?>
                                        </div>



                                                	</tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="form-group">
                                                           
                                                            <div class="col-sm-6">
                                                               <a href="index.php">Back</a>
                                                            </div>
                                                        </div>

                                </div>
                                <!-- /.row -->
  
                            </div>
                            <!-- /.container-fluid -->
                        <!-- </section> -->
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                  
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script>
            $(function($) {

            });
        </script>

        <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->

    </body>
</html>