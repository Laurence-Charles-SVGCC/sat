<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Transaction */

$this->title = 'Update Transaction: ' . ' ' . $model->transactionid;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transactionid, 'url' => ['view', 'id' => $model->transactionid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
