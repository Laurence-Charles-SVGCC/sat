<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionable Offers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
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
            <h3>Multiple Offers</h3>
            <?= GridView::widget([
                'dataProvider' => $multOfferDataProvider,
                'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['username'], 
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
                          }
                    ],
                    'firstname',
                    'lastname',
                    'programme',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'revokedate',
                    [
                        'attribute' => 'ispublished',
                        'format' => 'boolean',
                        'label' => 'Published'
                    ],
                ],
            ]); ?>
            <h3>CSEC English Language Requirement Violation</h3>
            <?= GridView::widget([
                'dataProvider' => $engReqDataProvider,
                'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['username'], 
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
                          }
                    ],
                    'firstname',
                    'lastname',
                    'programme',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'revokedate',
                    [
                        'attribute' => 'ispublished',
                        'format' => 'boolean',
                        'label' => 'Published'
                    ],
                ],
            ]); ?>
            
            <h3>CSEC Mathematics Requirement Violation</h3>
            <?= GridView::widget([
                'dataProvider' => $mathReqDataProvider,
                'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['username'], 
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
                          }
                    ],
                    'firstname',
                    'lastname',
                    'programme',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'revokedate',
                    [
                        'attribute' => 'ispublished',
                        'format' => 'boolean',
                        'label' => 'Published'
                    ],
                ],
            ]); ?>

            <h3>Minimum Entry Requirements Violation</h3>
            <?= GridView::widget([
                'dataProvider' => $subjectReqsDataProvider,
                'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['username'], 
                                       Url::to(['offer/view', 'id' => $row['offerid']]));
                          }
                    ],
                    'firstname',
                    'lastname',
                    'programme',
                    'issuedby',
                    'issuedate',
                    'revokedby',
                    'revokedate',
                    [
                        'attribute' => 'ispublished',
                        'format' => 'boolean',
                        'label' => 'Published'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


