<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TransactionPurpose */

$this->title = 'Create Transaction Purpose';
$this->params['breadcrumbs'][] = ['label' => 'Transaction Purposes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-purpose-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
