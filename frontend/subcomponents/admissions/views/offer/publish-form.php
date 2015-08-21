<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <h1>Publish Decisions</h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'divisionid')->dropDownList(
                    $divisions)->label('Division') ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'statustype')->dropDownList(
                    $statuses)->label('Publish Type') ?>
        </div>
    </div>
    <div class="form-group">
        <?= "Publishing Disabled At The Moment"//Html::submitButton('Publish', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
