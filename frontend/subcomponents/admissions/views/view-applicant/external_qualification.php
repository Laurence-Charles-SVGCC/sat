<?php

/* 
 * Author: Laurence Charles
 * Date Created: 03/04/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\PostSecondaryQualification;
    
    if(PostSecondaryQualification::getPostSecondaryQualifications($user->personid) == true)
        $this->title = 'Edit External Qualification';
    else
        $this->title = 'Add External Qualification';
    
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
                                'id' => 'save-external-qualification-form',
                                'options' => [
                                ],
                            ]);

                        echo "<table class='table table-hover' style='width:70%; margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Awarding Institution</th>";
                                echo "<td>{$form->field($qualification, 'awardinginstitution')->label("Awarding Institution*", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Name of Degree*</th>";
                                echo "<td>{$form->field($qualification, 'name')->label("Name of Degree*", ['class'=> 'form-label'])->textArea(['rows' => 5, 'maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Year Degree Awarded</th>";
                                echo "<td>{$form->field($qualification, 'yearawarded')->label("Year Degree Awarded *", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";                     
                        echo "</table><br/>";
                        
                        echo Html::a(' Cancel',['view-applicant/applicant-profile', 'applicantusername' => $user->username], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Update', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);
                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>



