<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $dasgs_programme_search_criteria = [
        '0' => 'Programmes',
        '1' => 'CAPE Subjects',
        '2' => 'All Programmes',
    ];
    
    $non_dasgs_programme_search_criteria = [
        '0' => 'Programmes',
        '1' => 'All Programmes',
    ];
    
    $this->title = 'Intake Reports Dashboard';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find A Student', 'url' => Url::toRoute(['/subcomponents/students/student/find-a-student'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['reports/generate-programme-intake'])]);?>
        <div class="box-body">
            <?= Html::hiddenInput('intake_listing_baseUrl', Url::home(true)); ?>
            
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
         </div>
   
        <div class="box-footer">
            <span class = "pull-right" id="intake-submit-button"  style="display:none">
                <?= Html::submitButton('Generate Report', ['class' => 'btn btn-md btn-success']) ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?><br/>
</div>