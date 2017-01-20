<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\TransactionItem;
    use frontend\models\PaymentMethod;
    use frontend\models\Semester;

    $this->title = "Enter Outstanding Payment";
    $this->params['breadcrumbs'][] = ['label' => 'Find'. ' ' .  ucwords($status), 'url' => ['payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]];
    $this->params['breadcrumbs'][] = ['label' => 'View Transactions', 'url' => ['payments/view-user-transactions', 'id' => $id, 'status' => $status]];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payments/find-applicant-or-student', 'status' => $status, 'new_search' => 1]);?>" title="Find Applicant">
        <h1><?= $this->title ?></h1>
    </a>
</div>

<div class="alert alert-info" role="alert">
    <span class="pull-left"><strong>Outstanding Balance: <?= $transaction->totaldue ?></strong></span>
    <span class="pull-right"><strong>Transaction Item: <?= TransactionItem::find()->where(['transactionitemid' => $transaction->transactionitemid])->one()->name; ?></strong></span><br/>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Installment Payment: <?= $name . "(" . $username . ")"?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="paymentmethodid">Payment Method:</label>
               <?= $form->field($transaction, 'paymentmethodid')->label('')->dropDownList( ArrayHelper::map(PaymentMethod::find()->all(), 'paymentmethodid', 'name'), [ 'prompt'=>'Select Payment Method', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="paydate">Date Of Payment:</label>
               <?= $form->field($transaction, 'paydate')->label('')->widget(
                        DatePicker::className(), [
                            'inline' => false,
                             // modify template for custom rendering
                            'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"
                            ]
                        ]); ?>
           </div>
            
           <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="amountpaid">Amount Paid:</label>
               <?=$form->field($transaction, 'paymentamount')->label('')->textInput(['maxlength' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="comments">Comments:</label>
               <?=$form->field($transaction, 'comments')->label('')->textArea(["rows" => 5, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
       </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['payments/view-user-transactions', 'id' => $id, 'status' => $status], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>