<?php

/* 
 * 'add_medical_condition' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 04/01/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Add Medical Condition';
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body"> 
                <h1 class="custom_h1">Add New Medical Condition</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'add-medical-condition-form',
                                'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                                ],
                            ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th>Name</th>";
                                echo "<td>{$form->field($condition, 'medicalcondition')->label('')->textArea(['rows' => '4'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th>Description</th>";
                                echo "<td>{$form->field($condition, 'description')->label('')->textArea(['rows' => '4'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th>Emergency Action</th>";
                                echo "<td>{$form->field($condition, 'emergencyaction')->label('')->textArea(['rows' => '4'])}</td>";
                            echo "</tr>";
                        echo "</table>"; 

                        echo Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>
