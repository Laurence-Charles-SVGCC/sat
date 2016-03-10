<?php

    /* 
     * 'edit_reference' view. 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $titles = [
            '' => 'Title', 
            'Mr' => 'Mr',
            'Ms' => 'Ms', 
            'Mrs' => 'Mrs'
        ];
    
    if ($action == "create")
        $this->title = 'Create Nurse Work Experience';
    else
        $this->title = 'Update Nurse Work Experience';
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
                            'id' => 'update-nurse-work-experience',
                            'options' => [
                            ],
                        ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Nature Of Training</th>";
                                echo "<td>{$form->field($nurseExperience, "natureoftraining")->label("Describe Post(s) and Responsibilities *", ['class'=> 'form-label'])->textArea(['rows' => '3', 'placeholder'=>'Indicate the name of the institution as well a your main responsibilities'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Location</th>";
                                echo "<td>{$form->field($nurseExperience, "location")->label("Address *", ['class'=> 'form-label'])->textArea(['rows' => '3'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Tenure Period</th>";
                                echo "<td>{$form->field($nurseExperience, "tenureperiod")->label("Length Of Time *", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Depart Reason</th>";
                                echo "<td>{$form->field($nurseExperience, "departreason")->label("Reason for leaving (if applicable) *", ['class'=> 'form-label'])->textArea(['rows' => '3'])}</td>";
                            echo "</tr>";
                        echo "</table>";
                            
                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>


