<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use frontend\models\Division;
use frontend\models\AcademicYear;
use frontend\models\ProgrammeCatalog;

/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationPeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-period-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="body-content">
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'divisionid')->dropDownList(
                            ArrayHelper::map(Division::find()->all(), 'divisionid', 'name'), 
                    [
                        'prompt'=>'Select Division',
                        ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'academicyearid')->dropDownList(
                            ArrayHelper::map(AcademicYear::find()->all(), 'academicyearid', 'title'), 
                        ['prompt'=>'Select Academic Year' ]) ?>
                </div>
            </div>
            
        <div class="row">              
                <div class="col-lg-4">
                    <?= $form->field($model, 'onsitestartdate')->widget(
                        DatePicker::className(), [
                            'inline' => false, 
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>
                </div>
               <div class="col-lg-4">     
                    <?= $form->field($model, 'onsiteenddate')->widget(
                        DatePicker::className(), [
                            'inline' => false, 
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'offsitestartdate')->widget(
                        DatePicker::className(), [
                            'inline' => false, 
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>
                </div>
                  <div class="col-lg-4">
                    <?= $form->field($model, 'offsiteenddate')->widget(
                        DatePicker::className(), [
                            'inline' => false, 
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]); ?>
                  </div>
          </div>
            
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
