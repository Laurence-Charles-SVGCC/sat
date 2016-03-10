<?php

    /* 
     * 'teacher_experience' view. 
     * Author: Laurence Charles
     * Date Created: 10/03/2016
     */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\ActiveForm;
    
    if ($action == "create")
        $this->title = 'Create New Teaching Role';
    else
        $this->title = 'Update Teaching Role';
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
                <h1 class="custom_h1"><?=$this->title?></h1>

                <?php
                    $form = ActiveForm::begin([
                            'id' => 'update-teacher-experience',
                            'options' => [
                            ],
                        ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Name of Institution</th>";
                                echo "<td>{$form->field($experience, 'institutionname')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Address</th>";
                                echo "<td>{$form->field($experience, 'address')->label('')->textArea(['rows' => '4'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Start Date</th>";
                                echo "<td>{$form->field($experience, 'startdate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Date of Appointment</th>";
                                echo "<td>{$form->field($experience, 'dateofappointment')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>End Date</th>";
                                echo "<td>{$form->field($experience, 'enddate')->label('')->widget(DatePicker::className(), ['inline' => false, 'template' => '{addon}{input}', 'clientOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Class Taught</th>";
                                echo "<td>{$form->field($experience, 'classtaught')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Subject(s)</th>";
                                echo "<td>{$form->field($experience, 'subject')->label('')->textInput(['maxlength' => true])}</td>";
                                echo "</tr>";
                        echo "</table>";
                            
                            
                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>


