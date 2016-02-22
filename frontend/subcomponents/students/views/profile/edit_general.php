<?php

/* 
 * 'edit_general' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 25/12/2015
 */

    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\StudentStatus;

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
                    <h1 class="custom_h1">Edit General Information</h1>
                    
                    <?php
                        $form = ActiveForm::begin([
                                    //'action' => Url::to(['gradebook/index']),
                                    'id' => 'edit-general-information-form',
                                    'options' => [
    //                                    'class' => 'form-layout form-inline'
    //                                    'class' => 'form-inline',
                                    ],
                                ]);
                    
                            echo "<br/>";
                                echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                    echo "<tr>";
                                        echo "<th>Student ID</th>";
                                            echo "<td>$general->username</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                            echo "<td>{$form->field($general, 'title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>First Name</th>";
                                            echo "<td>{$form->field($general, 'firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Middle Name</th>";
                                            echo "<td>{$form->field($general, 'middlename')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Last Name</th>";
                                            echo "<td>{$form->field($general, 'lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Gender</th>";
                                            echo "<td>{$form->field($general, 'gender')->label('')->dropDownList(['male' => 'Male', 'female' => 'Female'])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Date Of Birth</th>";
                                            echo "<td>{$form->field($general, 'dateofbirth')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Marital Status</th>";
                                        echo "<td>{$form->field($general, 'maritalstatus')->label('')->radioList(Yii::$app->params['maritalstatus'])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Nationality</th>";
                                        echo "<td>{$form->field($general, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'])}</td>";
                                    echo "</tr>";
                                    
                                    echo "<tr>";
                                        echo "<th style='width:300px;'>Place Of Birth</th>";
                                        echo "<td>{$form->field($general, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'])}</td>";
                                    echo "</tr>";
                                    
                                    echo "<tr>";
                                        echo "<th>Religion</th>";
                                        echo "<td>{$form->field($general, 'religion')->label('')->dropDownList(Yii::$app->params['religion'])}</td>";
                                    echo "</tr>";
                                    
                                    echo "<tr>";
                                        echo "<th>Sponsor Name</th>";
                                        echo "<td>{$form->field($general, 'sponsorname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";
                                    
                                    echo "<tr>";
                                        echo "<th>Student Status</th>";
                                        echo "<td>{$form->field($general, 'studentstatusid')->label('')->dropDownList(StudentStatus::getStatuses())}</td>";
                                    echo "</tr>";
                                echo "</table>"; 
                                
                                echo "<br/>";
                                                                                                
                                echo Html::a(' Cancel',['profile/student-profile', 'personid' => $general->personid, 'studentregistrationid' => $general->studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                                echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                                
                                
                            ActiveForm::end();    
                    ?>
                
                </div>
            </div>
        </div>
    </div>
        
                    

