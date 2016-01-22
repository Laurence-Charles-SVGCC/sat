<?php

/* 
 * 'Course_Detail' view of any course [Associate || CAPE] for users with authorization to edit assessments
 * Author: Laurence Charles
 * Date Created: 12/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\bootstrap\Modal;
    
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudent;
    
    /* @var $this yii\web\View */
    $this->title = 'Course Details';
    $this->params['breadcrumbs'][] = ['label' => 'Gradebook', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Listing', 'url' => ['index', 'id' => $divisionid]];
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['students',
                                                                               'academicyearid' => $academicyearid, 
                                                                               'academicofferingid' => $academicofferingid,
                                                                               'programmename' => $programmename, 
                                                                               'divisionid' => $divisionid 
                                                                            ]];
    $this->params['breadcrumbs'][] = ['label' => 'Transcript', 'url' => ['transcript',
                                                                           'personid' => $personid, 
                                                                           'studentregistrationid' => $studentregistrationid
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
                    <h1 class="custom_h1"><?=$code?>: <?=$name?></h1>
                    
                    <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                        <!-- Default panel contents -->
                        <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Profile Summary</div>
                    
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
                            </tr>                      
                        </table><br/>
                        
                        
                        <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Course Details</div>
                        <table class="table" style="width:95%; margin: 0 auto;">
                            <tr>
                                <th>Code</th>
                                <td><?=$course_details['code']?></td>
                                <th>Name</th>
                                <td><?=$course_details['name']?></td>
                                <th>Course Type</th>
                                <td><?=$course_details['type']?></td>
                            </tr>
                            
                            <tr>
                                <th>Pass Criteria</th>
                                <td><?=$course_details['pass_criteria']?></td>
                                <th>GPA Consideration</th>
                                <td><?=$course_details['pass_fail_type']?></td>
                                <th>Credits</th>
                                <td><?=$course_details['credits']?></td>
                            </tr>
                            
                            <tr>
                                <th>Coursework Weighting</th>
                                <td><?=$course_details['courseworkweight']?></td>
                                <th>Exam Weighting</th>
                                <td><?=$course_details['examweight']?></td>
                                <th>Passmark</th>
                                <td><?=$course_details['passmark']?></td>
                            </tr>
                        </table><br/>
                    </div>
                    
                    <?php 
                        if ($assessments)
                        {
                            echo"<h2 class='custom_h2'>Assessment Details</h2>";
                        
                            echo "<table class='table table-bordered' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                    $count = count($assessments);
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['name']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Assessment Category</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['category']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Assessment Type</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['type']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Group/Individual</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['participation']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Lecturer</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['lecturer']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Weight(%)</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['weight']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Date Administered</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['date']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Marks Attained</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['marks_attained']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Total Marks</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        echo "<td>{$assessments[$i]['total_marks']}</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Questions Link</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {   
                                        if (strcmp($assessments[$i]['questions'],"") != 0 || $assessments[$i]['questions'] != NULL)
                                            echo "<td>{$assessments[$i]['questions']}</td>";
                                        else
                                            echo "<td>File not uploaded</td>";
                                    }
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Mark Scheme</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
                                        if (strcmp($assessments[$i]['markscheme'],"") != 0 || $assessments[$i]['markscheme'] != NULL)
                                            echo "<td><a href=''>{$assessments[$i]['markscheme']}</a></td>";
                                        else
                                            echo "<td>File not uploaded</td>";
                                    }
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th>Action</th>";
                                    for ($i = 0 ; $i < $count ; $i++)
                                    {
//                                      
                                        $hyperlink = Url::toRoute(['/subcomponents/gradebook/gradebook/edit-assessments', 
                                                                                                           'studentregistrationid' => $assessments[$i]['registrationid'],
                                                                                                           'assessmentid' => $assessments[$i]['assessmentid'], 
                                                                                                           'code' => $code,
                                                                                                           'name' => $name,
                                                                        ]);
                                        echo "<td><a class='btn btn-info glyphicon glyphicon-pencil' href='$hyperlink' role='button'> Edit</a></td>";
                                        
                                        //For rendering with modal
//                                        echo "<td>";
//                                        
//                                        echo Html::button('Edit',['value' => Url::to(['/subcomponents/gradebook/gradebook/edit-assessments',
//                                                                                        'studentregistrationid' => $assessments[$i]['registrationid'],
//                                                                                        'assessmentid' => $assessments[$i]['assessmentid'], 
//                                                                                        'code' => $code,
//                                                                                        'name' => $name]),
//                                                                                        'class' => 'btn btn-info glyphicon glyphicon-pencil', 
//                                                                                        'style' => 'width:40%; margin-left:25%;',
//                                                                                        'id' => 'modalButton',
//                                                                                                                         
//                                                                 ]);
//                                        echo "</td>";
                                    
                                
                                
                                    }
                                echo "</tr>";
                            echo "</table>"; 
                        }
                        else
                        {
                            echo"<h2 class='custom_h2'>No assessment records have been entered.</h2>";
                        }
                    ?>
    
                </div>
            </div>
        </div>
        <?php 
            Modal::begin([
                'header' => '<h3>Edit Assessment</h4>',
                'id' => 'modal',
                'size' => 'modal-lg',
            ]);
                echo "<div id='modalContent'</div>";
            
            Modal::end();
        ?>
    </div>


