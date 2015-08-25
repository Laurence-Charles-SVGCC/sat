<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offer Issues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h3>Multiple Offers</h3>
    <?= GridView::widget([
        'dataProvider' => $multOfferDataProvider,
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
    <h3>CSEC English Language Requirement Violation</h3>
    <?= GridView::widget([
        'dataProvider' => $engReqDataProvider,
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
    
    <h3>Minimum Entry Requirements Violation</h3>
    <?= GridView::widget([
        'dataProvider' => $subjectReqsDataProvider,
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
