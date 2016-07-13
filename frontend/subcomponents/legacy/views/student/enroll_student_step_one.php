<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyTerm;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyBatchType;
    use frontend\models\LegacyLevel;
    use frontend\models\Employee;


     $this->title = 'Student Enrollment Configuration';
     $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => ['index']];
     $this->params['breadcrumbs'][] = $this->title;
     
     $no_of_students = [
         0 => 0,
         1 => 1,
         2 => 2,
         3 => 3,
         4 => 4,
         5 => 5,
         6 => 6,
         7 => 7,
         8 => 8,
         9 => 9,
         10 => 10,
         11 => 11,
         12 => 12,
         13 => 13,
         14 => 14,
         15 => 15,
         16 => 16,
         17 => 17,
         18 => 18,
         19 => 19,
         20 => 20,
     ];
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/legacy/legacy/index']);?>" title="Manage Legacy Records">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/legacy.png" alt="legacy avatar">
                <span class="custom_module_label" > Welcome to the Legacy Management System</span> 
                <img src ="css/dist/img/header_images/legacy.png" alt="legacy avatar" class="pull-right">
            </a>  
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title;?></h1>
            
            <?php $form = ActiveForm::begin([
//                    'action' => Url::to(['student/enroll-students-step-two']),
                    'id' => 'enroll-student-step-one',
                    'options' => [
                         'class' => 'form-layout',
                        'style' => 'width:70%;',
                        ]
                    ]); 
            ?>
            
                <?= Html::hiddenInput('enroll_baseUrl', Url::home(true)); ?>
            
                <div style='width:98%;margin:0 auto'><br/>
                    <div id='enroll-batch-type-div'>
                        <?= Html::label('1. Please select batch type: ', 'batch_type_label'); ?>
                        <?= Html::dropDownList('enroll_batch_type_field',  null, ArrayHelper::map(LegacyBatchType::find()->all(), 'legacybatchtypeid', 'name'), ['prompt' => 'Select..', 'id' => 'enroll_batch_type', 'onchange' => 'toggleEnrollBatchTypeDiv();']) ; ?>
                    </div></br>
                    
                    <div id='enroll-level-div' style='display:none'>
                        <?= Html::label('2. Please select level: ', 'subject_level'); ?>
                        <?= Html::dropDownList('enroll_level_field',  null, ArrayHelper::map(LegacyLevel::find()->all(), 'legacylevelid', 'name'), ['prompt' => 'Select..', 'id' => 'enroll_level', 'onchange' => 'toggleEnrollLevelDiv();']) ; ?>
                    </div></br>
                    
                    <div id='enroll-subject-type-div' style='display:none'>
                        <?= Html::label('3. Please select subject type: ', 'subject_type_label'); ?>
                        <?= Html::dropDownList('enroll_subject_type_field',  null, ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt' => 'Select..', 'id' => 'enroll_subject_type', 'onchange' => 'toggleEnrollSubjectTypeDiv();PrepareEnrollSubjectListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='enroll-subject-div' style='display:none'>
                        <?= Html::label('4. Please select subject: ', 'subject_label'); ?>
                        
                        <?= Html::dropDownList('enroll_subject_field', 'Select...', ['' => 'Select...'], ['prompt' => 'Select..', 'id' => 'enroll_subject', 'onchange' => 'toggleEnrollSubjectDiv();']) ; ?>
                       
                    </div></br>
                    
                    <div id='enroll-year-div' style='display:none'>
                        <?= Html::label('5. Please select year: ', 'year_label'); ?>
                        <?= Html::dropDownList('enroll_year_field',  null, ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt' => 'Select..', 'id' => 'enroll_year', 'onchange' => 'toggleEnrollYearDiv();PrepareEnrollTermListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='enroll-term-div' style='display:none'>
                        <?= Html::label('6. Please select term: ', 'term_label'); ?>
                        <?= Html::dropDownList('enroll_term_field', 'Select...', ['' => 'Select...'], ['prompt' => 'Select..', 'id' => 'enroll_term', 'onchange' => 'toggleEnrollTermDiv();']) ; ?>
                    </div></br>
                    
                    <div id='enroll-student-count-div' style='display:none'>
                        <?= Html::label('7. Please the number of students you wish to enroll: ', 'student_count_label'); ?>
                        <?= Html::dropDownList('enroll_student_count_field', null, $no_of_students, ['id' => 'enroll_student_count', 'onchange' => 'toggleEnrollStudentCountDiv();']) ; ?>
                    </div></br>
                    
                    <span id='enroll-submit-button' style='display:none'>
                        <?= Html::a(' Cancel', ['student/index'], ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:15%; margin-right:2.5%'] );?>
                    
                        <?= Html::submitButton(' Next', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:15%;']);?>
                    </span>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


