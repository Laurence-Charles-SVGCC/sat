<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\Club;
    use frontend\models\Division;
    
    $this->title = $action . " Club";
    $this->params['breadcrumbs'][] = ['label' => 'Club Listing', 'url' => Url::toRoute(['/subcomponents/registry/clubs/manage-clubs'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <?=$form->field($club, 'name')->label('', ['class'=> 'form-label'])->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="description">Description:</label>
               <?=$form->field($club, 'description')->label('', ['class'=> 'form-label'])->textArea(["rows" => 5, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="motto">Motto:</label>
               <?=$form->field($club, 'motto')->label('', ['class'=> 'form-label'])->textArea(['rows' => 5, "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9" ])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="yearfounded">Year Founded:</label>
               <?=$form->field($club, 'yearfounded')->label('', ['class'=> 'form-label'])->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="'divisionid">Division:</label>
               <?=$form->field($clubdivision, 'divisionid')->label('')->dropDownList(ArrayHelper::map(Division::find()->where(['divisionid' => [1,4,5,6,7]])->all(), 'divisionid', 'name'), ['prompt'=>'Select Division', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
           </div>
       </div>
        
         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['clubs/manage-clubs'], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>
