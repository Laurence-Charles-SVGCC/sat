<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PaymentMethod */

$this->title = 'Update Payment Method: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->paymentmethodid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
