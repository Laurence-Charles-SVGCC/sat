<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use frontend\models\TransactionType;
use frontend\models\TransactionPurpose;
use frontend\models\PaymentMethod;
use frontend\models\Semester;

?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="body-content">
            <?= Html::hiddenInput('payee_id', $payee_id); ?>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'transactiontypeid')->dropDownList(
                            ArrayHelper::map(TransactionType::find()->all(), 'transactiontypeid', 'name'), 
                    [
                        'prompt'=>'Select Transaction Type',
                        ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'transactionpurposeid')->dropDownList(
                            ArrayHelper::map(TransactionPurpose::find()->all(), 'transactionpurposeid', 'name'), 
                        ['prompt'=>'Select Transaction Purpose' ]) ?>
                </div>
            </div>
            
        <div class="row">              
                <div class="col-lg-4">
                    <?= $form->field($model, 'semesterid')->dropDownList(
                            ArrayHelper::map(Semester::find()->all(), 'semesterid', 'title'), 
                        ['prompt'=>'Select Semester' ]) ?>
                </div>
               <div class="col-lg-4">     
                    <?= $form->field($model, 'paymentmethodid')->dropDownList(
                            ArrayHelper::map(PaymentMethod::find()->all(), 'paymentmethodid', 'name'), 
                        ['prompt'=>'Select Payment Method' ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'paydate')->widget(
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
                    <?= $form->field($model, 'paymentamount')->textInput(); ?>
                  </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'totaldue')->textInput(); ?>
                 </div>
                <div class="col-lg-4">
                   <?= $form->field($model, 'comments')->textArea(); ?>
                 </div>
          </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
