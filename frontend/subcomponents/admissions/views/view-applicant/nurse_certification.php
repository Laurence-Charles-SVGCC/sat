<?php

    /* 
     * 'NursePriorCertification' view. 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\datepicker\DatePicker;
    use yii\widgets\ActiveForm;
    
    if ($action == "create")
        $this->title = 'Create New Nurse Prior Certification';
    else
        $this->title = 'Update Nurse Prior Certification';
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                    <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                    <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
                </a>   
            </div>
            
            <div class="custom_body">
                <h1 class="custom_h1"><?=$this->title?></h1>

                <?php
                    $form = ActiveForm::begin([
                            'id' => 'update-nurse-certification',
                            'options' => [
                            ],
                        ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Certification</th>";
                                echo "<td>{$form->field($experience, 'certification')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Dates Of Training</th>";
                                echo "<td>{$form->field($experience, 'datesoftraining')->label('')->textArea(['rows' => '3'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Length Of Training</th>";
                                echo "<td>{$form->field($experience, 'lengthoftraining')->label('')->textArea(['rows' => '3'])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Nme Of Institution</th>";
                                echo "<td>{$form->field($experience, 'institutionname')->label('')->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                        echo "</table>";
                            
                            
                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>

