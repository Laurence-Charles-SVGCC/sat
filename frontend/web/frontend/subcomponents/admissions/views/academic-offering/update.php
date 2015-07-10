<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */

$this->title = 'Update Academic Offering: ' . ' ' . $model->academicofferingid;
$this->params['breadcrumbs'][] = ['label' => 'Academic Offerings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->academicofferingid, 'url' => ['view', 'id' => $model->academicofferingid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="academic-offering-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
