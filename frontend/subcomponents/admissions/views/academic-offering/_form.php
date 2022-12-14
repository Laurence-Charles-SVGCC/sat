<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use frontend\models\AcademicYear;
use frontend\models\ApplicationPeriod;
use frontend\models\ProgrammeCatalog;
use frontend\models\QualificationType;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="academic-offering-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'academicyearid')->dropDownList(
                    ArrayHelper::map(AcademicYear::find()->all(), 'academicyearid', 'title'), ['prompt'=>'Select Academic Year']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'applicationperiodid')->dropDownList(
                    ArrayHelper::map(ApplicationPeriod::find()->all(), 'applicationperiodid', 'name'), ['prompt'=>'Select Application Period']) ?>
        </div>
    </div>
    
    <div class="row">
            <div class="col-lg-4">
                <h3>Programmes</h3>
            </div>
    </div>
    <?php foreach(ProgrammeCatalog::findAll(['isdeleted' => 0]) as $programme): ?>
        <?php $qualification = QualificationType::findOne(['qualificationtypeid' => $programme->qualificationtypeid]) ?>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'programmecatalogid['. $programme->programmecatalogid .']')->checkbox(
                    ['label' => $qualification->abbreviation . ' ' . $programme->name . ' '. $programme->specialisation ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'spaces['. $programme->programmecatalogid .']')->textInput() ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'interviewneeded['. $programme->programmecatalogid .']')->checkbox(['label' => 'Interview Needed']) ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="row">
            <div class="col-lg-4">
                <h3>Cape Subjects</h3>
            </div>
    </div>
    <?php foreach($capesubjects as $csub): ?>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($capesubject, 'subjectname['. $csub->name .']')->checkbox(['label' => $csub->name]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($capesubject, 'capacity['. $csub->name .']')->textInput() ?>
            </div>
            <div class="col-lg-4">
                <?= Html::label('Group', 'capegroup'); ?>
                <?= Html::checkboxList('capegroup['. $csub->name .']', null, array(1,2,3,4,5,6,7)) ?>
            </div>
        </div>
    <hr>
    <?php endforeach; ?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('updateAcademicOffering') || Yii::$app->user->can('createAcademicOffering')): ?>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
