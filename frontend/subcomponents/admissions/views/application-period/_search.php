<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationPeriodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-period-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'applicationperiodid') ?>

    <?= $form->field($model, 'divisionid') ?>

    <?= $form->field($model, 'personid') ?>

    <?= $form->field($model, 'academicyearid') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'onsitestartdate') ?>

    <?php // echo $form->field($model, 'onsiteenddate') ?>

    <?php // echo $form->field($model, 'offsitestartdate') ?>

    <?php // echo $form->field($model, 'offsiteenddate') ?>

    <?php // echo $form->field($model, 'isactive')->checkbox() ?>

    <?php // echo $form->field($model, 'isdeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
