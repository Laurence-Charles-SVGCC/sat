<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    $this->title = $operation . " Payment Method";
    $this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/payments/payment-method/index']);?>" title="Payment Method Home">
        <h1>Welcome to the Payment Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin([ "options" => []]);?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?=$form->field($payment_method, 'name')->label('')->textInput(['maxlength' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
       </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['payment-method/index'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>
