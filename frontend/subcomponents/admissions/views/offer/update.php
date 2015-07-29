<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Offer */

$this->title = 'Update Offer: ' . ' ' . $model->offerid;
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->offerid, 'url' => ['view', 'id' => $model->offerid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
