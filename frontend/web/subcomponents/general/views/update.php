<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Division */

$this->title = 'Update Division: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->divisionid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="division-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
