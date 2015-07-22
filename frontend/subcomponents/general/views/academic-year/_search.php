<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicYearSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="academic-year-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'academicyearid') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'iscurrent')->checkbox() ?>

    <?= $form->field($model, 'startdate') ?>

    <?= $form->field($model, 'enddate') ?>

    <?php // echo $form->field($model, 'isactive')->checkbox() ?>

    <?php // echo $form->field($model, 'isdeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
