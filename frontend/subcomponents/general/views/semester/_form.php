<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Semester */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semester-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'academicyearid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'startdate')->textInput() ?>

    <?= $form->field($model, 'enddate')->textInput() ?>

    <?= $form->field($model, 'iscurrent')->checkbox() ?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('updateSemester') || Yii::$app->user->can('createSemester')): ?>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
