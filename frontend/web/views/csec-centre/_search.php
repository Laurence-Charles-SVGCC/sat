<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="csec-centre-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cseccentreid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'cseccode') ?>

    <?= $form->field($model, 'isactive')->checkbox() ?>

    <?= $form->field($model, 'isdeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
