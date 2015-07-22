<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicYear */

$this->title = 'Update Academic Year: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Academic Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->academicyearid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="academic-year-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
