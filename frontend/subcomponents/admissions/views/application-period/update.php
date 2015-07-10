<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationPeriod */

$this->title = 'Update Application Period: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->applicationperiodid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="application-period-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
