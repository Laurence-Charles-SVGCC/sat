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


     $this->title = 'Batch Selection';
     $this->params['breadcrumbs'][] = $this->title;
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
                    'id' => 'grade-entry-step-one',
                    'options' => [
                         'class' => 'form-layout',
                        'style' => 'width:70%;',
                        ]
                    ]); 
            ?>
            
                <?= Html::hiddenInput('grades_baseUrl', Url::home(true)); ?>
            
                <div style='width:98%;margin:0 auto'><br/>
                    <div id='grades-batch-type-div'>
                        <?= Html::label('1. Please select batch type: ', 'batch_type_label'); ?>
                        <?= Html::dropDownList('grades_batch_type_field',  null, ArrayHelper::map(LegacyBatchType::find()->all(), 'legacybatchtypeid', 'name'), ['prompt' => 'Select..', 'id' => 'grades_batch_type', 'onchange' => 'toggleGradesBatchTypeDiv();']) ; ?>
                    </div></br>
                    
                    <div id='grades-level-div' style='display:none'>
                        <?= Html::label('2. Please select level: ', 'subject_level'); ?>
                        <?= Html::dropDownList('grades_level_field',  null, ArrayHelper::map(LegacyLevel::find()->all(), 'legacylevelid', 'name'), ['prompt' => 'Select..', 'id' => 'grades_level', 'onchange' => 'toggleGradesLevelDiv();']) ; ?>
                    </div></br>
                    
                    <div id='grades-subject-type-div' style='display:none'>
                        <?= Html::label('3. Please select subject type: ', 'subject_type_label'); ?>
                        <?= Html::dropDownList('grades_subject_type_field',  null, ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt' => 'Select..', 'id' => 'grades_subject_type', 'onchange' => 'toggleGradesSubjectTypeDiv();PrepareGradesSubjectListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='grades-subject-div' style='display:none'>
                        <?= Html::label('4. Please select subject: ', 'subject_label'); ?>
                        
                        <?= Html::dropDownList('grades_subject_field', 'Select...', ['' => 'Select...'], ['id' => 'grades_subject', 'onchange' => 'toggleGradesSubjectDiv();']) ; ?>
                       
                    </div></br>
                    
                    <div id='grades-year-div' style='display:none'>
                        <?= Html::label('5. Please select year: ', 'year_label'); ?>
                        <?= Html::dropDownList('grades_year_field',  null, ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt' => 'Select..', 'id' => 'grades_year', 'onchange' => 'toggleGradesYearDiv();PrepareGradesTermListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='grades-term-div' style='display:none'>
                        <?= Html::label('6. Please select term: ', 'term_label'); ?>
                        <?= Html::dropDownList('grades_term_field', 'Select...', ['' => 'Select...'], ['id' => 'grades_term', 'onchange' => 'toggleGradesTermDiv();']) ; ?>
                    </div></br>
                    
                    <span id='grades-submit-button' style='display:none'>
                        <?= Html::submitButton(' Next', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:15%;']);?>
                    </span>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


