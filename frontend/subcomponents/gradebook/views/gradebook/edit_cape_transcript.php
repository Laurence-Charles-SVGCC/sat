<?php

/* 
 * 'edit_cape_transcript' view 
 * Author: Laurence Charles
 * Date Created: 16/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\BatchStudentCape;
    use frontend\models\StudentRegistration;
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
                <h1 class="custom_h1">Edit Course Totals</h1>

                <?php 
                    if ($course_record == NULL || $course_record == false || $course_summary == NULL || $course_summary == false )
                    {
                        echo "<h1 class='custom_h2'>Course can not be editted at this time</h1>";
                    }

                    else
                    {
                        $form = ActiveForm::begin([
                            //'action' => Url::to(['gradebook/index']),
                            'id' => 'edit-cape-transcript-form',
                            'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                            ],
                        ]);

                            echo "<table class='table table-hover table-bordered' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>Course Code</th>";
                                        echo "<td>{$course_summary['code']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Course Name</th>";
                                        echo "<td>{$course_summary['name']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Credits Unit</th>";
                                        echo "<td>{$course_summary['unit']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Subject</th>";
                                        echo "<td>{$course_summary['subject']}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Coursework ({$course_summary['courseworkweight']}%)</th>";
                                         echo "<td>{$form->field($course_record, 'courseworktotal')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Exam ({$course_summary['examweight']}%)</th>";
                                         echo "<td>{$form->field($course_record, 'examtotal')->label('')->textInput(['maxlength' => true])}</td>";

                                echo "<tr>";
                                    echo "<th>Final</th>";
                                        echo "<td>{$course_summary['final']}</td>";
                                echo "</tr>";

                            echo "</table>";

                            echo "<br/>";                                
                            echo Html::a(' Cancel',['edit-transcript-cancel', 'batchid' => $course_summary['batchid'], 'studentregistrationid' => $course_summary['studentregistrationid']], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                        ActiveForm::end();      
                    }                    
                ?>
            </div>
        </div>
    </div>
                        

