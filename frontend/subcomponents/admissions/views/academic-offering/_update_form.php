<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\AcademicYear;
use frontend\models\ApplicationPeriod;
use frontend\models\ProgrammeCatalog;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */
/* @var $form yii\widgets\ActiveForm */

$pc_result = ProgrammeCatalog::findOne(['programmecatalogid' => $model->programmecatalogid]);
$ay_result = AcademicYear::findOne(['academicyearid' => $model->academicyearid]);
$ap_result = ApplicationPeriod::findOne(['applicationperiodid' => $model->applicationperiodid]);
?>

<div class="academic-offering-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <div class="row">
        <div class="col-lg-4">
            <?= Html::label('Academic Year: ' . $ay_result->title) ?>
        </div>
        <div class="col-lg-4">
            <?= Html::label('Application Period: ' . $ap_result->name) ?>
        </div>
        <div class="col-lg-4">
            <?= Html::label('Programme: ' . $pc_result->name) ?>
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-4">
            <?= $form->field($model, 'spaces')->textInput() ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'appliable')->checkbox(['label' => 'Appliable']) ?>
        </div>
    </div>

    <div class="form-group">
      <?php if (Yii::$app->user->can('updateAcademicOffering') || Yii::$app->user->can('createAcademicOffering')): ?>  
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
