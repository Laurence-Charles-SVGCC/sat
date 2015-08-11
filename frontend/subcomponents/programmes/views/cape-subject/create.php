<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProgrammeCatalog */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create CAPE Subject';
$this->params['breadcrumbs'][] = ['label' => 'CAPE Subject Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="programme-catalog-form">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('createCapeSubject')): ?>
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>