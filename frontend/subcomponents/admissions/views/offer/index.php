<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $divisionabbr . ' Offers for ' . $applicationperiodname;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Publish All Offers', ['publish-all'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Publish Rejects', ['publish-rejects'], ['class' => 'btn btn-danger']) ?>
    </p>

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
