<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="academic-offering-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'programmecatalogid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'academicyearid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'applicationperiodid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spaces')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appliable')->checkbox() ?>

    <?= $form->field($model, 'isactive')->checkbox() ?>

    <?= $form->field($model, 'isdeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
