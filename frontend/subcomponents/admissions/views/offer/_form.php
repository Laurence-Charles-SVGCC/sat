<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="offer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'applicationid')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('updateOffer') || Yii::$app->user->can('createOffer')): ?>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
