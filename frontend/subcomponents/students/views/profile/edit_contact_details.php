<?php

/* 
 * 'edit_contact_details' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    $this->title = 'Edit Contact Details';
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
                <div class="module_body">
                    <h1 class="custom_h1">Edit Contact Details</h1>
                    
                    <?php
                        $form = ActiveForm::begin([
                                    //'action' => Url::to(['gradebook/index']),
                                    'id' => 'edit-contact-details-form',
                                    'options' => [
    //                                    'class' => 'form-layout form-inline'
    //                                    'class' => 'form-inline',
                                    ],
                                ]);
                        
                            echo "<br/>";
                            echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<th>Home Phone</th>";
                                    echo "<td>{$form->field($phone, 'homephone')->label('')->textInput(['maxlength' => true])}</td>"; 
                                    echo "<th>Cell Phone</th>";
                                    echo "<td>{$form->field($phone, 'cellphone')->label('')->textInput(['maxlength' => true])}</td>";   
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<th>Work Phone</th>";
                                    echo "<td>{$form->field($phone, 'workphone')->label('')->textInput(['maxlength' => true])}</td>"; 
                                    echo "<th>Personal Email</th>";
                                    echo "<td>{$form->field($email, 'email')->label('')->textInput(['maxlength' => true])}</td>";  
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<th>Institutional Email</th>";
                                    echo "<td>{$form->field($student, 'email')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "<th></th>";
                                    echo "<td></td>";   
                                echo "</tr>";
                            echo "</table>";
                            echo "<br/>";

                            echo Html::a(' Cancel',['profile/student-profile', 'personid' => $student->personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                            
                        ActiveForm::end();    
                    ?>
                
                </div>
            </div>
        </div>
    </div>
                    

