<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="csec-centre-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'cseccode')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php if (Yii::$app->user->can('updateCsecCentre') || Yii::$app->user->can('createCsecCentre')): ?>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
