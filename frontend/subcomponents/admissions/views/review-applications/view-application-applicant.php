<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

//$type = ucfirst($type);
$this->title = 'Applicant Information';
$this->params['breadcrumbs'][] = ['label' => 'Review Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['review-applications/update-view']),
                    ]
                    ); ?>
                
                <?= Html::hiddenInput('application_status', $application_status); ?>
                <?= Html::hiddenInput('division_id', $division_id); ?>
                
                
                <div class="body-content">
                    <?php if(count($programmes) > 1):?>
                        <br/><p style="font-size:20px">If you wish to filter the results by programme, use the dropdownlist below.</p>

                        <div class="row">
                            <div class="col-lg-8">

                                <?= Html::label( 'Select Filtering Criteria',  'programme'); ?>
                                <?= Html::dropDownList('programme', null, 
                                    //array_merge(['0' => 'None'] , $programmes)
                                        $programmes, [ 'style' => 'font-size:20px', 'onchange' => 'showUpdateButton();']
                                        ) ; ?>
                            </div>

                            <div class="col-lg-4" id="update-button" style="display:none">
                                <?= Html::submitButton('Update View', ['class' => 'btn btn-success']) ?>
                            </div> <br/> 

                            <!--TODO: Investigate how to sort dataProvider by multiple levels and implement Gamal Crichton 27/07/2015-->
                            <!--<div class="col-lg-3">
                                <?php Html::label( 'First Priority',  'first_priority'); ?>
                                <?php Html::dropDownList('first_priority', null, 
                                    array('none' => 'None', 'subjects_no' => 'No. of Subjects', 'ones_no' => 'No. of 1s', 
                                        'twos_no' => 'No. of 2s', 'threes_no' => 'No. of 3s')); ?>
                            </div>
                            <div class="col-lg-3">
                               <?php Html::label( 'Second Priority',  'second_priority'); ?>
                                <?php Html::dropDownList('second_priority', null, 
                                    array('none' => 'None', 'subjects_no' => 'No. of Subjects', 'ones_no' => 'No. of 1s', 
                                        'twos_no' => 'No. of 2s', 'threes_no' => 'No. of 3s')); ?>
                            </div>
                            <div class="col-lg-3">
                               <?php Html::label( 'Third Priority',  'third_priority'); ?>
                                <?php Html::dropDownList('third_priority', null, 
                                    array('none' => 'None', 'subjects_no' => 'No. of Subjects', 'ones_no' => 'No. of 1s', 
                                        'twos_no' => 'No. of 2s', 'threes_no' => 'No. of 3s')); ?>
                            </div>-->
                        </div>
                    <?php endif;?>
                </div>
            
            <?php ActiveForm::end(); ?>
            <?php if ($results) : ?>
            <?= "Results" ?>
                <?= $this->render('_results', [
                    'dataProvider' => $results,
                    'application_status' => $application_status,
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>