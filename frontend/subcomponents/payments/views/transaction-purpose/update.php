<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TransactionPurpose */

$this->title = 'Update Transaction Purpose: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Purposes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->transactionpurposeid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-purpose-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
