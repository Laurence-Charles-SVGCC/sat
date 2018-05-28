<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacySubjectType;

     $this->title = 'Create New Subject';
     $this->params['breadcrumbs'][] = ['label' => 'Subject Listing', 'url' => ['index']];
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/subjects/index']);?>" title="Legacy Subjects">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name:</label>
               <span><?=$form->field($subject, 'name')->label('')->textInput([ "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
            
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacysubjecttypeid">Examination Body:</label>
               <span><?=$form->field($subject, 'legacysubjecttypeid')->label('')->dropDownList(ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt'=>'Select Examination Body..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?></span>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['subjects/index'],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>