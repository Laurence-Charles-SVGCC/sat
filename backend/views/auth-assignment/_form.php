<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use backend\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'item_name')->dropDownList(
            ArrayHelper::map(AuthItem::find()->all(), 'name', 'name'), ['prompt'=>'Select Role or Permission']) ?>
    <?= $form->field($model, 'user_id')->dropDownList(
            $employees, ['prompt'=>'Select user']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
