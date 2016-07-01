<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use yii\helpers\ArrayHelper;

$this->title = 'Add Registration';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alternate-offer">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= $firstname . " " . $middlename . " " . $lastname . "(" . $username . ")" ?></h2>
    <h3>Student's Applications</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'order',
                'format' => 'text',
                'label' => 'Choice Order'
            ],
            [
                'attribute' => 'applicationid',
                'format' => 'text',
                'label' => 'Application ID',
            ],
            [
                'attribute' => 'programme_name',
                'format' => 'text',
                'label' => 'Programme',
            ],
        ],
    ]); ?>
    <?php if (Yii::$app->user->can('createOffer')): ?>
        <h3>New Registration Details</h3>
        <?php ActiveForm::begin(
                [
                    'action' => Url::to(['student/add-registration']),
                ]
                ); ?>
                <?= Html::hiddenInput('division_id', $division_id); ?>
                <?= Html::hiddenInput('username', $username); ?>
                <div class="row">
                    <div class="col-lg-2">
                        <?= Html::label( 'Choose Programme',  'programme'); ?>
                        <?= Html::dropDownList('programme', null, $programmes); ?>
                    </div>
                </div>
                <div class="row">
                    <h4>CAPE Groups</h4>
                        <?php foreach($cape_data as $grp_name => $cd): ?>
                            <div class="col-md-3">
                                <strong><?= $grp_name ?></strong>
                            <?php foreach($cd as $subject): ?>                           
                                <br/><?= Html::checkbox("cape_subject[" . $subject->getCapesubject()->one()->capesubjectid . "]"); ?>
                                <?= $subject->getCapesubject()->one()->subjectname; ?>                        
                             <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                </div>
                <?= Html::submitButton("Submit", ['class' => 'btn btn-success']); ?>
        <?php ActiveForm::end() ?>
    <?php endif; ?>
</div>