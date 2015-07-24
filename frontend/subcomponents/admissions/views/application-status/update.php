<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationStatus */

$this->title = 'Update Application Status: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Application Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->applicationstatusid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="application-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
