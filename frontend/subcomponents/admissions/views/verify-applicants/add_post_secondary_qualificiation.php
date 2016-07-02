<?php

/* 
 * 'add_post_secondary_qualification' view.  
 * Author: Laurence Charles
 * Date Created: 15/03/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\PostSecondaryQualification;
    
    $this->title = 'Add Post Secondary Qualification';
    
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
                                'id' => 'save-new-post-secondary-qualification-form',
                                'options' => [
                                ],
                            ]);

                        echo "<table class='table table-hover' style='width:70%; margin: 0 auto;'>";                                        
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Name of Degree*</th>";
                                echo "<td>{$form->field($qualification, 'name')->label(" ", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                            
                            echo "<tr>";
                                echo "<th style='vertical-align:middle'>Awarding Institution</th>";
                                echo "<td>{$form->field($qualification, 'awardinginstitution')->label(" ", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<th style='vertical-align:middle;'>Year Degree Awarded</th>";
                                echo "<td>{$form->field($qualification, 'yearawarded')->label(" ", ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";                     
                        echo "</table><br/>";
                        
                        echo Html::submitButton(' Save', ['class' => 'glyphicon glyphicon-ok btn btn-block btn-lg btn-success', 'style' => 'width:70%; margin:0 auto;']);

                    ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>

