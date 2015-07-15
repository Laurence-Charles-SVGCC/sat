<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentre */

$this->title = 'Update CSEC Centre: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'CSEC Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->cseccentreid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="csec-centre-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
