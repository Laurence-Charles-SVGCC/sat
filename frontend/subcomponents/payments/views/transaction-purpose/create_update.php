<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;

    $this->title = $operation . " Transaction Purpose";
    $this->params['breadcrumbs'][] = ['label' => 'Transaction Purposes', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?=$form->field($transaction_purpose, 'name')->label('')->textInput(['maxlength' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="description">Description:</label>
               <?=$form->field($transaction_purpose, 'description')->label('')->textArea(['maxlength' => true, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", "rows" => 3])?>
           </div>
       </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['transaction-purpose/index'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>