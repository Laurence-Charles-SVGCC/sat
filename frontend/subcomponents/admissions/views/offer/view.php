<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Offer */

$this->title = $model->offerid;
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->offerid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Revoke', ['delete', 'id' => $model->offerid], [
            'class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'offerid',
            'applicationid',
            'issuedby',
            'issuedate',
            'revokedby',
            'revokedate',
            'ispublished:boolean',
        ],
    ]) ?>

</div>
