<?php

/* 
 * 'add_qualification' view.  Used for modifying information in the 'General' section of 'Profile' tab
 * Author: Laurence Charles
 * Date Created: 28/02/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\CsecCentre;
    use frontend\models\ExaminationBody;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\Subject;
    
    $this->title = 'Edit Qualification';
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
                <h1 class="custom_h1">Edit Qualification Details</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'edit-csec-qualification-form',
                                'options' => [
                                ],
                            ]);

                        echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Examination Centre</th>";
                                echo "<td>{$form->field($qualification, 'cseccentreid')->label('')->dropDownList(CsecCentre::processCentres(), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Candidtate Number</th>";
                                echo "<td>{$form->field($qualification, 'candidatenumber')->label('')->textInput(['maxlength' => true, 'style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Examination Body</th>";
                                echo "<td>{$form->field($qualification, 'examinationbodyid')->label('')->dropDownList(ExaminationBody::processExaminationBodies(), ['onchange' => 'EditCsecQualificationAjaxFunction(event);', 'style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Subject</th>";
                                echo "<td>{$form->field($qualification, 'subjectid')->label('')->dropDownList(Subject::initializeSubjectDropdown($qualification->csecqualificationid), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Proficiency</th>";
                                echo "<td>{$form->field($qualification, 'examinationproficiencytypeid')->label('')->dropDownList(ExaminationProficiencyType::initializeProficiencyDropdown($qualification->csecqualificationid), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Grade</th>";
                                echo "<td>{$form->field($qualification, 'examinationgradeid')->label('')->dropDownList(ExaminationGrade::initializeGradesDropdown($qualification->csecqualificationid), ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Examination Year</th>";
                                echo "<td>{$form->field($qualification, 'year')->label('')->dropDownList(Yii::$app->params['years'], ['style'=> 'font-size:14px;'])}</td>";
                            echo "</tr>";                     
                        echo "</table>";

                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>

