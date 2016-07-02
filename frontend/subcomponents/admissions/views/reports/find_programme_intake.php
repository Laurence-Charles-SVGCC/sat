<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Intake Reports Dashboard';
    //$this->params['breadcrumbs'][] = $this->title;
   
    $dasgs_programme_search_criteria = [
        '0' => 'Programmes',
        '1' => 'CAPE Subjects',
    ];
    
    $non_dasgs_programme_search_criteria = [
        '0' => 'Programmes',
    ];
    
?>

<div class="report-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <?php
                $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['reports/generate-programme-intake']),
                    ]); 
            ?>
            
                <?= Html::hiddenInput('intake_listing_baseUrl', Url::home(true)); ?>
                
                <div style="margin-left:2.5%"><br/>
                    <div id="intake-application-period">
                        <?= Html::label('1. Please select the application period you wish to investigate: ', 'intake_period_label'); ?>
                        <?= Html::dropDownList('intakeperiod',  "Select...", $periods, ['id' => 'intake_period_field', 'onchange' => 'toggleIntakeProgrammeOptions();']) ; ?>
                    </div></br>
                    
                    <div id="dasgs-intake-programme-options" style="display:none">
                        2. Please select programme search criteria.
                        <?= Html::radioList('dasgs_programme_search_criteria', null, $dasgs_programme_search_criteria, ['class'=> 'form_field', 'onclick'=> 'toggleDASGSIntakeSearchCriteria();IntakePrepareListing(event);']);?>
                    </div>
                    
                    
                    <div id="non-dasgs-intake-programme-options" style="display:none">
                        2. Please select programme search criteria.
                        <?= Html::radioList('non_dasgs_programme_search_criteria', null, $non_dasgs_programme_search_criteria, ['class'=> 'form_field', 'onclick'=> 'toggleIntakeSearchCriteria();IntakePrepareListing(event);']);?>
                    </div>
                    
                    <br/>
                    
                   
                    <div id="intake-all-programmes" style="display:none">
                        <?= Html::label('3. Programmes Listing: ', 'programme_label'); ?>
                        <?= Html::dropDownList('prog',  "Select...", ['' => 'Select...'], ['id' => 'programme_field', 'onchange' => 'toggleIntakeSearchButton();']) ; ?>
                    </div>
                    
                    <div id="intake-cape-listing" style="display:none">
                        <?= Html::label('3. Cape Subject Listing: ', 'subject_label'); ?>
                        <?= Html::dropDownList('subj',  "Select...", ['' => 'Select...'], ['id' => 'subject_field', 'onchange' => 'toggleIntakeSearchButton();']) ; ?>
                    </div>

                    <div id="intake-submit-button"  style="display:none">
                        <br/><?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                    </div>
                    
                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
