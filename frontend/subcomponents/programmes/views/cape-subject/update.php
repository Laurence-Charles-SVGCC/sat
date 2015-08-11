<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProgrammeCatalog */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update CAPE Subject';
$this->params['breadcrumbs'][] = ['label' => 'CAPE Subject Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="programme-catalog-form">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'isactive')->checkbox() ?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('updateCapeSubject')): ?>
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>