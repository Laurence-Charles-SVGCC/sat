<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\DocumentType;

$this->title = 'Applicant Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="offer-form">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(
            [
                'action' => Url::to(['register-student/make-student']),
            ]); ?>
    <div class="row">
        <?= Html::hiddenInput('applicantid', $applicant->applicantid); ?>
        <?= Html::hiddenInput('offerid', $offerid); ?>
        <?= Html::hiddenInput('applicationid', $applicationid); ?>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'firstname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'middlename')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($applicant, 'lastname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'gender')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'dateofbirth')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($applicant, 'maritalstatus')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'nationality')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($applicant, 'placeofbirth')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <h3>Documents Checklist</h3>
    <div class="row">
        <div class="col-lg-2">
            <?= Html::checkboxList('documents', $selections, 
                    ArrayHelper::map(DocumentType::findAll(['isdeleted' => 0]), 'documenttypeid', 'name')) ?>
        </div>
    </div>
    
    <div class="form-group">
        <?php if (Yii::$app->user->can('registerStudent')): ?>
            <?= Html::submitButton($applicant->isNewRecord ? 'Create' : 'Update', ['class' => $applicant->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
