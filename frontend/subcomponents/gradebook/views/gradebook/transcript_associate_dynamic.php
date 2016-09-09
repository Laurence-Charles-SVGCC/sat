<?php

/* 
 * 'Transcipt' view of Associate programs for users with authorization to view
 * Author: Laurence Charles
 * Date Created: 09/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudent;
    use frontend\models\StudentRegistration;
    
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
                    <img class="custom_logo" src ="css/dist/img/header_images/grade_a+.png" alt="A+">
                    <span class="custom_module_label">Welcome to the SVGCC Grade Management System</span> 
                    <img src ="css/dist/img/header_images/grade_a+.png" alt="A+">
                </a>        
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Academic Transcript : <?= $student->firstname . " " . $student->lastname?></h1>

                <table class="table" style="width:95%; margin: 0 auto;">
                    <tr>
                        <td rowspan="4"> 
                            <?php if($applicant->photopath == NULL || strcmp($applicant->photopath, "") ==0 ): ?>
                                <?php if (strcasecmp($student->gender, "male") == 0): ?>
                                    <img src="css/dist/img/avatar_male(150_150).png" alt="avatar_male" class="img-rounded">
                                <?php elseif (strcasecmp($student->gender, "female") == 0): ?>
                                    <img src="css/dist/img/avatar_female(150_150).png" alt="avatar_female" class="img-rounded">
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
                        <th>Level / Academic Status</th>
                        <td><?= $studentregistration->currentlevel?> / <?=AcademicStatus::getStatus($studentregistration->academicstatusid)?></td>
                        <th>Cumlative GPA</th>
                        <td><?=$cumulative_gpa?></td>
                    </tr>
                    <tr>
                        <th>Programme Details</th>
                        <td><?=$programme_description?></td>
                    </tr>  
                </table>
                <br/>

                <?php
                    $semester_count = BatchStudent::getSemesterCount($studentregistration->studentregistrationid);

                    /*An unsorted array of associative arrays where each associative array holds a
                     *[semester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]*/
                    $semester_info_unsorted = BatchStudent::getSemesters($studentregistration->studentregistrationid);

                    /*A sorted array of associative arrays where each associative array holds a
                     *[semester_title'=> $semester.title, 'academic_year_title' =>  $academic_year.title]*/
                    $semester_info_sorted = BatchStudent::sortSemesters($semester_info_unsorted);

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
                                    echo "<th>Credits Attempted</th>";
                                    echo "<th>Credits Awarded</th>";
                                    echo "<th>CW</th>";
                                    echo "<th>Exam</th>";
                                    echo "<th>Final</th>";                                      
                                    echo "<th>Grade</th>";
                                    echo "<th>Grade Points</th> "; 
                                    echo "<th>Course Status</th>";
                                    echo "<th>Action</th>";
                                echo "</tr>";

                                $course_results = BatchStudent::getSemesterRecords($semester_id);
                                $credits_sum = 0;
                                $points_sum = 0;
                                $courses_count = BatchStudent::getCourseCount($semester_id);
                                $valid_courses_count = BatchStudent::getValidCourseCount($semester_id);
                                for ($j = 0 ; $j < $courses_count ; $j++)
                                {  
                                    echo "<tr>";
                                        $iscape = 0;
                                        $batchid = $course_results[$j]['batchid'];
                                        $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/assessments', 
                                                                                                        'iscape' => $iscape, 
                                                                                                        'batchid' => $batchid, 
                                                                                                        'studentregistrationid' => $studentregistration->studentregistrationid,
                                                                                                        'code' => $course_results[$j]['code'],
                                                                                                        'name' => $course_results[$j]['name']
                                                                    ]);
                                        echo "<td><a href='$hyperlink'>{$course_results[$j]['code']}</a></td>";
                                        echo "<td>{$course_results[$j]['name']}</td>";
                                        echo "<td>{$course_results[$j]['credits_attempted']}</td>";
                                        if (strcmp($course_results[$j]['course_status'], "P") == 0)       
                                            echo "<td>{$course_results[$j]['credits_awarded']}</td>";                                     
                                        else
                                            echo "<td>0</td>";  
                                        echo "<td>{$course_results[$j]['courseworktotal']}</td>";
                                        echo "<td>{$course_results[$j]['examtotal']}</td>";
                                        echo "<td>{$course_results[$j]['final']}</td>";
                                        echo "<td>{$course_results[$j]['grade']}</td>";
                                        echo "<td>{$course_results[$j]['gradepoints']}</td>";
                                        echo "<td>{$course_results[$j]['course_status']}</td>";
                                        $hyperlink_edit = Url::toRoute(['/subcomponents/gradebook/gradebook/edit-transcript', 
                                                                                                        'batchid' => $batchid, 
                                                                                                        'studentregistrationid' => $studentregistration->studentregistrationid,
                                                                    ]);
                                        echo "<td><a class='btn btn-info glyphicon glyphicon-pencil' href='$hyperlink_edit' role='button'> Edit</a></td>";
                                        if (strcmp($course_results[$j]['course_status'], "P") == 0)
                                        {
                                            $points_sum += $course_results[$j]["gradepoints"];  
                                            $credits_sum += $course_results[$j]["credits_awarded"];  
                                        }
                                    echo "</tr>";
                                }

                                $semester_gpa = $points_sum/$valid_courses_count;
                                echo "<tr>";
                                    echo "<th colspan='2'>Credits Attained</th>";
                                    echo "<td colspan='2'>$credits_sum<td>";
                                    echo "<th colspan='3'>Semester GPA<th>";
                                    echo "<td colspan='2'>$semester_gpa</td>";                                 
                                echo "</tr>";                           
                            echo "</table>";                               
                        echo "</div><br/><br/>";                        
                    }
                ?>
            </div>
        </div>
    </div>


