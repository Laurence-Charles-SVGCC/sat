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


     $this->title = 'Create Batch';
     $this->params['breadcrumbs'][] = ['label' => 'Academic Year Listing', 'url' => ['index']];
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
                    /*'action' => Url::to(['reports/generate-programme-intake']),*/
                    'id' => 'create-subject',
                    'options' => [
                         'class' => 'form-layout',
                        'style' => 'width:70%;',
                        ]
                    ]); 
            ?>
            
                <?= Html::hiddenInput('batch_baseUrl', Url::home(true)); ?>
            
                <div style='width:98%;margin:0 auto'><br/>
                    <div id='batch-type-div'>
                        <?= Html::label('1. Please select batch type: ', 'batch_type_label'); ?>
                        <?= Html::dropDownList('batch_type_field',  null, ArrayHelper::map(LegacyBatchType::find()->all(), 'legacybatchtypeid', 'name'), ['prompt' => 'Select..', 'id' => 'batch_type', 'onchange' => 'toggleBatchTypeDiv();']) ; ?>
                    </div></br>
                    
                    <div id='level-div' style='display:none'>
                        <?= Html::label('2. Please select level: ', 'subject_level'); ?>
                        <?= Html::dropDownList('level_field',  null, ArrayHelper::map(LegacyLevel::find()->all(), 'legacylevelid', 'name'), ['prompt' => 'Select..', 'id' => 'level', 'onchange' => 'toggleLevelDiv();']) ; ?>
                    </div></br>
                    
                    <div id='subject-type-div' style='display:none'>
                        <?= Html::label('3. Please select subject type: ', 'subject_type_label'); ?>
                        <?= Html::dropDownList('subject_type_field',  null, ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt' => 'Select..', 'id' => 'subject_type', 'onchange' => 'toggleSubjectTypeDiv();PrepareSubjectListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='subject-div' style='display:none'>
                        <?= Html::label('4. Please select subject: ', 'subject_label'); ?>
                        
                        <?= Html::dropDownList('subject_field', 'Select...', ['' => 'Select...'], ['prompt' => 'Select..', 'id' => 'subject', 'onchange' => 'toggleSubjectDiv();']) ; ?>
                       
                    </div></br>
                    
                    <div id='year-div' style='display:none'>
                        <?= Html::label('5. Please select year: ', 'year_label'); ?>
                        <?= Html::dropDownList('year_field',  null, ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt' => 'Select..', 'id' => 'year', 'onchange' => 'toggleYearDiv();PrepareTermListing(event)']) ; ?>
                    </div></br>
                    
                    <div id='term-div' style='display:none'>
                        <?= Html::label('6. Please select term: ', 'term_label'); ?>
                        <?= Html::dropDownList('term_field', 'Select...', ['' => 'Select...'], ['prompt' => 'Select..', 'id' => 'term', 'onchange' => 'toggleTermDiv();']) ; ?>
                    </div></br>
                    
                    
                    <span id='submit-button' style='display:none'>
                        <?= Html::a(' Cancel', ['year/index'], ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:15%; margin-right:2.5%'] );?>
                    
                        <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:15%;']);?>
                    </span>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
