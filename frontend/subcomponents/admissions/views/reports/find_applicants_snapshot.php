<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Applicant Snapshot Generator';
    //$this->params['breadcrumbs'][] = $this->title;
    
    $report_categories = [
        '0' => 'Programme',
        '1' => 'Applicant Summary',
        '2' => 'Exception Reports',
    ];
    
    $period_scope = [
        '0' => 'Application Period Specific',
        '1' => 'Application Period Aggregate',
    ];
    
    $dasgs_programme_search_criteria = [
        '0' => 'All Programmes',
        '1' => 'Associate Programmes',
        '2' => 'CAPE Subjects',
    ];
            
     $none_dasgs_programme_search_criteria = [
        '0' => 'All Programmes'
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
           
            <?php $form = ActiveForm::begin();?>
            
                <div class="alert in alert-block fade alert-info mainButtons" style="width:95%; margin: 0 auto">
                    This report generator is intended for uses to generate a snapshot report of Applicant programme choices based on
                    the name of the programme and priority of the choice.  Please select the programmes you wish to investigate from the
                    checklist and their priority.
                </div>
            
                <div style="width:95%; margin: 0 auto"><br/>
                    <fieldset>
                        <legend>1. Select one or more programmes for search:</legend>
                        <div class="row">
                            <div class="col-lg-4">
                                <?= Html::checkboxList('offerings', null, $listing, ['style' => 'display:block ;float:left']);?>
                            </div>
                        </div>
                    </fieldset><br/>

                    <fieldset>
                        <legend>2. Select priority of programme search:</legend>
                        <div class="row">
                            <div class="col-lg-3">
                                <?= Html::radioList('ordering', null, [1 => 'First Choice', 2 => 'Second Choice', 3 => 'Child Choice'], ['class'=> 'form_field']);?>
                            </div>
                        </div>
                    </fieldset>
                        
                     <div class="form-group">
                        <br/><?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: left']) ?>
                    </div>
                    
                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
