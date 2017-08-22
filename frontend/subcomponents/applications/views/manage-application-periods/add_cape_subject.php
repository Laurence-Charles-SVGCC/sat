<?php

/* 
 * Author: Laurence Charles
 * Date Created 11/02/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use yii\helpers\ArrayHelper;
    use frontend\models\IntentType;
    use frontend\models\ExaminationBody;
    use frontend\models\QualificatinoType;
    use frontend\models\Department;
    
    $this->title = 'Add CAPE Subject to Insitution Catalog';
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
            <h1 class="custom_h1">Add New CAPE Subject</h1>
            
            <br/>
            <div style="width:70%; margin: 0 auto; font-size: 20px;">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'add-cape-subject-form',
                        'options' => [
//                                            'class' => 'form-layout'
                        ],
                    ]);

                        echo "<br/>";
                        echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";
                            echo "<tr>";
                                echo "<th style='width:30%; vertical-align:middle'>Name</th>";
                                    echo "<td>{$form->field($subject, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            echo "</tr>";
                        echo "</table>"; 

                        echo "<br/>";
                        echo Html::a(' Cancel',['admissions/period-setup-step-three'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);        
                    ActiveForm::end();    
                ?>
            </div>
            
        </div>
    </div>
</div>


