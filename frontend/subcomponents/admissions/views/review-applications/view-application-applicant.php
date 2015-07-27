<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

//$type = ucfirst($type);
$this->title = 'Applicant Information';
//$this->params['breadcrumbs'][] = ['label' => 'Manage Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(
            [
                'action' => Url::to(['review-applications/update-view']),
            ]
            ); ?>
        <div class="body-content">
            <?= "Filter Criteria" ?>
            <div class="row">
                <?= Html::hiddenInput('application_status', $application_status); ?>
                <?= Html::hiddenInput('division_id', $division_id); ?>
                <div class="col-lg-3">
                    <?= Html::label( 'Programmes',  'programme'); ?>
                    <?= Html::dropDownList('programme', null, 
                        array_merge(['0' => 'None'] , ArrayHelper::map($programmes, 'programmecatalogid', 'name' ))
                            ) ; ?>
                </div>
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
        </div>
    <div class="form-group">
        <?= Html::submitButton('Update View', ['class' => 'btn btn-success']) ?>
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