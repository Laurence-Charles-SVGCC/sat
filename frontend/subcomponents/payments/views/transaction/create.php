<?php

use yii\helpers\Html;

$this->title = 'New Payment';
//$this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment">

    <h1><?= Html::encode($this->title) ?> </h1>
    <?= $this->render('_form', [
        'model' => $model,
        'payee_id' => $payee_id,
         'transactionsummaryid' => $transactionsummaryid,
    ]) ?>
    
</div>