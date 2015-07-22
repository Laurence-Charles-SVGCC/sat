<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Semester */

$this->title = 'Update Semester: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Semesters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->semesterid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="semester-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
