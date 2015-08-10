<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\PersonType;
use frontend\models\Department;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'firstname' )->textInput()->label('First Name'); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'lastname')->textInput()->label('Last Name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'email')->textInput()->label('College Email'); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'password')->passwordInput()->label('Password'); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'confirm_password')->passwordInput()->label('Confirm Password'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'username')->textInput()->label('Username (if assigned)'); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'persontypeid')->dropDownList(
                    ArrayHelper::map(PersonType::findAll(['isactive' => 1]), 'persontypeid', 'persontype'))->label('Person Type') ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'department')->dropDownList(
                    ArrayHelper::map(Department::findAll(['isactive' => 1]), 'departmentid', 'name'))->label('Department') ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
