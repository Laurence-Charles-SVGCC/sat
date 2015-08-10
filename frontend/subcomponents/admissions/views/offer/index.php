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
        <?php if (Yii::$app->user->can('publishOffer')): ?>
            <?= Html::a('Bulk Publish', ['bulk-publish'], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
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
