<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $divisionabbr . ' Offers for ' . $applicationperiodname;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <?php if($offer_issues): ?>
        <?= Html::a('Offer Issue Details', ['offer-issue-details'], ['class' => 'btn btn-danger']) ?>
    <?php endif; ?>
    
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>

            <div class="row">
                <div class="col-lg-9">
                    <?php $form = ActiveForm::begin(
                        [
                            'action' => Url::to(['offer/update-view']),
                        ]
                        ); ?>
                    <h3>Select either a Programme or a CAPE Subject to filter.</h3>
                        <?= Html::label( 'Programmes',  'programme'); ?>
                        <?= Html::dropDownList('programme', null, $programmes ) ; ?>

                        <?= Html::label( 'CAPE Subjects',  'cape'); ?>
                        <?= Html::dropDownList('cape', null, $cape_subjects) ; ?>

                        <?= Html::submitButton('Update View', ['class' => 'btn btn-success']) ?>
                    <?php ActiveForm::end(); ?>

                    <?php if (Yii::$app->user->can('publishOffer')): ?>
                        <?= Html::a('Bulk Publish', ['bulk-publish'], ['class' => 'btn btn-primary']) ?>
                    <?php endif; ?>
                    <?= Html::a('Export Valid Offers', ['export-valid-offers'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Export All Offers', ['export-all-offers'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>



            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'offerid',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['offerid'], 
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
                          }
                    ],
                    'applicationid',
                    'firstname',
                    'lastname',
                    'programme',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'ispublished:boolean',
                ],
            ]); ?>
        </div>
    </div>
</div>
