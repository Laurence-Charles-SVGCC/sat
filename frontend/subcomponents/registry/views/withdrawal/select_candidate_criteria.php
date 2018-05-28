<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\Department;
    
    $this->title = 'Withdrawal Listing Generation';
    
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin(['action' => Url::to(['withdrawal/generate-withdrawal-candidates'])]);?>
        <div class="box-body">
            <div class="form-group">
               <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
               <?= Html::dropDownList('period-id',  "Select...", $periods, []) ; ?>                                   
           </div>
        </div>
   
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:20px;']) ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>