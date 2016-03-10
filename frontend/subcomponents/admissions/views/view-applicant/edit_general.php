<?php

/* 
 * 'edit_general' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 28/02/2016
 */

    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Edit General';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                    <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                    <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1">Edit General Information</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'edit-general-information',
                                'options' => [
                                ],
                            ]);

                        echo "<br/>";
                            echo "<table class='table table-hover' style='width:95%; margin: 0 auto;'>";
                                echo "<tr>";
                                    echo "<th>Student ID</th>";
                                        echo "<td>$user->username</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Title</th>";
                                        echo "<td>{$form->field($applicant, 'title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                    echo "</tr>";

                                echo "<tr>";
                                    echo "<th>First Name</th>";
                                        echo "<td>{$form->field($applicant, 'firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Middle Name</th>";
                                        echo "<td>{$form->field($applicant, 'middlename')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($applicant, 'lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Gender</th>";
                                        echo "<td>{$form->field($applicant, 'gender')->label('')->dropDownList(['male' => 'Male', 'female' => 'Female'])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Date Of Birth</th>";
                                        echo "<td>{$form->field($applicant, 'dateofbirth')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Marital Status</th>";
                                    echo "<td>{$form->field($applicant, 'maritalstatus')->label('')->radioList(Yii::$app->params['maritalstatus'])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Nationality</th>";
                                    echo "<td>{$form->field($applicant, 'nationality')->label('')->dropDownList(Yii::$app->params['nationality'])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th style='width:300px;'>Place Of Birth</th>";
                                    echo "<td>{$form->field($applicant, 'placeofbirth')->label('')->dropDownList(Yii::$app->params['placeofbirth'])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Religion</th>";
                                    echo "<td>{$form->field($applicant, 'religion')->label('')->dropDownList(Yii::$app->params['religion'])}</td>";
                                echo "</tr>";

                                echo "<tr>";
                                    echo "<th>Sponsor Name</th>";
                                    echo "<td>{$form->field($applicant, 'sponsorname')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";
                            echo "</table>"; 

                            echo "<br/>";

                            echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                            echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);


                        ActiveForm::end();    
                    ?>
            </div>
        </div>
    </div>
        
                    

