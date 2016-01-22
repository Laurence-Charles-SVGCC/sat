<?php

/* 
 * 'Transcipt' view of cape programs for users with authorization to view
 * Author: Laurence Charles
 * Date Created: 14/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudentCape;
    
    /* @var $this yii\web\View */
    $this->title = 'Academic Transcript';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Listing', 'url' => ['index', 'id' => $divisionid]];
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['students',
                                                                               'academicyearid' => $academicyearid, 
                                                                               'academicofferingid' => $academicofferingid,
                                                                               'programmename' => $programmename, 
                                                                               'divisionid' => $divisionid 
                                                                            ]];
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Gradebook Home">     
                    <img class="custom_logo" src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                    <span class="custom_module_label">Welcome to the SVGCC Grade Management System</span> 
                    <img src ="<?=Url::to('../images/grade_a+.png');?>" alt="A+">
                </a>        
            </div>
            
            <div class="custom_body">                
                <div class="module_body">
                    <h1 class="custom_h1">Academic Transcript : <?= $student->firstname . " " . $student->lastname?></h1>
                    
                    <table class="table" style="width:95%; margin: 0 auto;">
                        <tr>
                            <td rowspan="3"> 
                                <?php if($applicant->photopath == NULL || strcmp($applicant->photopath, "") ==0 ): ?>
                                    <?php if (strcasecmp($student->gender, "male") == 0): ?>
                                        <img src="<?=Url::to('../images/avatar_male(150*150).png');?>" alt="avatar_male" class="img-rounded">
                                    <?php elseif (strcasecmp($student->gender, "female") == 0): ?>
                                        <img src="<?=Url::to('../images/avatar_female(150*150).png');?>" alt="avatar_female" class="img-rounded">
                                    <?php endif;?>
                                <?php else: ?>
                                        <img src="<?=$applicant->photopath;?>" alt="student_picture" class="img-rounded">
                                <?php endif;?>
                            </td>
                            <th>Student ID</th>
                            <td><?=$person->username?></td>
                            <th>Full Name</th>
                            <td><?=$student->title . ". " . $student->firstname . " " . $student->middlename . " " . $student->lastname ?>                       
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td><?=$student->dateofbirth?></td>
                            <th>Gender</th>
                            <td><?=$student->gender ?>
                        </tr>
                        <tr>
                            <th>Academic Status</th>
                            <td><?=AcademicStatus::getStatus($studentregistration->academicstatusid)?></td>
                            <th>Programme Details</th>
                            <td><?=$programme_description?></td>
                        </tr>                      
                    </table>
                    <br/>
                    
                    <?php
                        $semester_count = BatchStudentCape::getSemesterCount($studentregistration->studentregistrationid);
                        
                        /*An unsorted array of associative arrays where each associative array holds a
                         *[semester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]*/
                        $semester_info_unsorted = BatchStudentCape::getSemesters($studentregistration->studentregistrationid);
                        
                        /*A sorted array of associative arrays where each associative array holds a
                         *[semester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]*/
                        $semester_info_sorted = BatchStudentCape::sortSemesters($semester_info_unsorted);
                        
                        for ($i = 0 ; $i < $semester_count ; $i++)
                        {
                            $semester_id = $semester_info_sorted[$i]["semester_id"];
                            $semester_title = $semester_info_sorted[$i]['semester_title'];
                            $academicyear_title = $semester_info_sorted[$i]['academic_year_title'];
                            echo "<br><div style='width:95%; margin: 0 auto;'>";
                                echo "<p>Academic Year: $academicyear_title, Semester: $semester_title</p>";
                                echo "<table class='table table-hover table-bordered'>";
                                    echo "<tr>";
                                        echo "<th>Course Code</th>";
                                        echo "<th>Course Name</th>";
                                        echo "<th>Unit</th>";
                                        echo "<th>Subject</th>";
                                        echo "<th>Coursework</th>";
                                        echo "<th>Exam</th>";
                                        echo "<th>Final</th>";                    
                                    echo "</tr>";
                                    
                                    $course_results = BatchStudentCape::getSemesterRecords($semester_id);
                                   
                                    $courses_count = BatchStudentCape::getCourseCount($semester_id);
                                    
                                    for ($j = 0 ; $j < $courses_count ; $j++)
                                    {  
                                        echo "<tr>";
                                            echo "<td>{$course_results[$j]['code']}</td>";
                                            echo "<td>{$course_results[$j]['name']}</td>";
                                            echo "<td>{$course_results[$j]['unit']}</td>";
                                            echo "<td>{$course_results[$j]['subject']}</td>";
                                            echo "<td>{$course_results[$j]['courseworktotal']}</td>";
                                            echo "<td>{$course_results[$j]['examtotal']}</td>";
                                            echo "<td>{$course_results[$j]['final']}</td>";                                             
                                        echo "</tr>";
                                    }                          
                                echo "</table>";                               
                            echo "</div><br/><br/>";                        
                        }
                    ?>
     
                </div>
            </div>
        </div>
    </div>



