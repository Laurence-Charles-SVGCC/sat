<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOfferingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="academic-offering-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'academicofferingid') ?>

    <?= $form->field($model, 'programmecatalogid') ?>

    <?= $form->field($model, 'academicyearid') ?>

    <?= $form->field($model, 'applicationperiodid') ?>

    <?= $form->field($model, 'spaces') ?>

    <?php // echo $form->field($model, 'appliable')->checkbox() ?>

    <?php // echo $form->field($model, 'isactive')->checkbox() ?>

    <?php // echo $form->field($model, 'isdeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
