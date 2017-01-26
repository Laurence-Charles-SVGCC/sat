<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudent;
    use frontend\models\StudentRegistration;
    use frontend\models\Offer;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicYear;
    use frontend\models\ApplicationCapesubject;
    
    $this->title = 'Academic Transcript';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    if (Yii::$app->user->can('Cordinator') == false)
        $this->params['breadcrumbs'][] = ['label' => 'Programme Listing', 'url' => ['index', 'id' => $divisionid]];
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['students',
                                                                               'academicyearid' => $academicyearid, 
                                                                               'academicofferingid' => $academicofferingid,
                                                                               'programmename' => $programmename, 
                                                                               'divisionid' => $divisionid 
                                                                            ]];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/gradebook/gradebook/index']);?>" title="Grade Management Home">
        <h1>Welcome to the SVGCC Grade Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center">Academic Transcript : <?= $student->firstname . " " . $student->lastname?></h2>
    
    <table class="table">
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
            <td><?= $studentregistration->currentlevel?> / <?= $academic_status; ?>
            <th>Cumlative GPA</th>
            <td><?=$cumulative_gpa?></td>
        </tr>
        <tr>
            <th>Programme Details</th>
            <td><?=$programme_description?></td>
        </tr>  
    </table>
    <br/>

    <?php if (count($enrollments) > 1):?>
        <p class="alert alert-info" role="alert" style="width:95%; margin: 0 auto; font-size:16px;">
            Student has multiple registration records. You can view alternative registration(s) using the dropdownlist
            labeled "Select Enrollment Record".
        </p><br/>
    <?php endif;?>

    <?php if (count($enrollments) > 1):?>
        <div class="dropdown pull-right" style="margin-right:2.5%">
            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                <strong>Select Alternative Enrollment Record...</strong>
                <span class='caret'></span>
            </button>
            <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                <?php
                    foreach ($enrollments as $enrollment)
                    {
                        if ($studentregistration->studentregistrationid != $enrollment->studentregistrationid)
                        {
                            $offer = Offer::find()
                                    ->where(['offerid' => $enrollment->offerid, 'isdeleted' => 0])
                                    ->one();
                            $current_cape_subjects_names = array();                
                            $current_cape_subjects = array();
                            $current_application = $offer->getApplication()->one();
                            $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                            $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                            foreach ($current_cape_subjects as $cs)
                            { 
                                $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                            }
                            $current_programme = empty($current_cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $current_cape_subjects_names);

                            $academic_year = AcademicYear::find()
                                    ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                                    ->where(['academic_year.isdeleted' => 0,
                                                    'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $current_application->academicofferingid
                                                ])
                                    ->one()
                                    ->title;
                            $label = "(" . $academic_year . ")  " . $current_programme;

                            $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/transcript/', 
                                                            'personid' => $person->personid,
                                                            'studentregistrationid' => $enrollment->studentregistrationid
                                                         ]);
                            echo "<li><a href='$hyperlink' target='_blank'>$label</a></li>";  
                        }
                    }
                ?>
            </ul>
        </div>
    <?php endif;?>


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
                        echo "<th>Course Status</th>";
                        echo "<th>Grade</th>";
                        echo "<th>Quality Points</th> "; 
                        echo "<th>Grade Points</th> "; 
                        if (Yii::$app->user->can('editTranscript') == true)      
                        {
                            echo "<th>Action</th>";
                        }
                    echo "</tr>";

                    $course_results = BatchStudent::getSemesterRecords($studentregistration->studentregistrationid, $semester_id);
                    $credits_sum = 0;
                    $points_sum = 0;
                    $courses_count = BatchStudent::getCourseCount($studentregistration->studentregistrationid, $semester_id);
                    $valid_courses_count = BatchStudent::getValidCourseCount($studentregistration->studentregistrationid, $semester_id);
                    for ($j = 0 ; $j < $courses_count ; $j++)
                    {  
                        $grade_points = $course_results[$j]['credits_attempted'] * $course_results[$j]['qualitypoints'];
                        echo "<tr>";
                            $iscape = 0;
                            $batchid = $course_results[$j]['batchid'];

                            if (Yii::$app->user->can('accessCourseDetails') == true)      //if user has access to see course assessment summary
                            {
                                $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/assessments', 
                                                                                                'iscape' => $iscape, 
                                                                                                'batchid' => $batchid, 
                                                                                                'studentregistrationid' => $studentregistration->studentregistrationid,
                                                                                                'code' => $course_results[$j]['code'],
                                                                                                'name' => $course_results[$j]['name']
                                                            ]);
                                echo "<td><a href='$hyperlink'>{$course_results[$j]['code']}</a></td>";
                            }
                            else
                            {
                                echo "<td>{$course_results[$j]['code']}</td>";
                            }
                            echo "<td>{$course_results[$j]['name']}</td>";
                            echo "<td>{$course_results[$j]['credits_attempted']}</td>";
                            if (strcmp($course_results[$j]['course_status'], "P") == 0)       
                                echo "<td>{$course_results[$j]['credits_awarded']}</td>";                                     
                            else
                                echo "<td>0</td>";  
                            echo "<td>{$course_results[$j]['courseworktotal']}</td>";
                            echo "<td>{$course_results[$j]['examtotal']}</td>";
                            echo "<td>{$course_results[$j]['final']}</td>";
                            echo "<td>{$course_results[$j]['course_status']}</td>";
                            echo "<td>{$course_results[$j]['grade']}</td>";
                            echo "<td>{$course_results[$j]['qualitypoints']}</td>";
                            echo "<td>$grade_points</td> "; 


                            if (Yii::$app->user->can('editTranscript') == true)      
                            {
                                $hyperlink_edit = Url::toRoute(['/subcomponents/gradebook/gradebook/edit-transcript', 
                                                                                            'batchid' => $batchid, 
                                                                                            'studentregistrationid' => $studentregistration->studentregistrationid,
                                                        ]);
                                echo "<td><a class='btn btn-info glyphicon glyphicon-pencil' href='$hyperlink_edit' role='button'> Edit</a></td>";
                            }

                            if (strcmp($course_results[$j]['course_status'], "P") == 0)
                            {
                                $credits_sum += $course_results[$j]["credits_awarded"];  
                            }
                        echo "</tr>";
                    }

                    $semester_gpa = BatchStudent::getSemesterGPA($studentregistration->studentregistrationid, $semester_id);
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