<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

use frontend\models\ExaminationBody;
use frontend\models\Department;
use frontend\models\QualificationType;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProgrammeCatalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programme-catalog-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'specialisation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'examinationbodyid')->dropDownList(
                            ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'name'), 
                    [
                        'prompt'=>'Select Examining Body',
                        ]) ?>

    <?= $form->field($model, 'qualificationtypeid')->dropDownList(
                            ArrayHelper::map(QualificationType::find()->all(), 'qualificationtypeid', 'name'), 
                    [
                        'prompt'=>'Select Qualification',
                        ]) ?>

    <?= $form->field($model, 'departmentid')->dropDownList(
                            ArrayHelper::map(Department::find()->all(), 'departmentid', 'name'), 
                    [
                        'prompt'=>'Select Department',
                        ]) ?>

    <?= $form->field($model, 'creationdate')->widget(
                        DatePicker::className(), [
                            'inline' => false, 
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
