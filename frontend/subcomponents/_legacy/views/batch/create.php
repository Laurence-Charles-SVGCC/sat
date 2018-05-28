<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyTerm;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyBatchType;
    use frontend\models\LegacyLevel;
    use frontend\models\Employee;

     $this->title = 'Create New Batch';
     $this->params['breadcrumbs'][] = ['label' => 'Batch Listing', 'url' => ['index']];
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/batch/index']);?>" title="Legacy Batches">
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
        <?= Html::hiddenInput('batch_baseUrl', Url::home(true)); ?>
    
        <div class="box-body">
            <div class="form-group" id='level-div'>
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacylevelid">Level: </label>
               <span><?= Html::dropDownList('level_field',  null, ArrayHelper::map(LegacyLevel::find()->all(), 'legacylevelid', 'name'), ['prompt' => 'Select..', 'id' => 'level', 'onchange' => 'toggleLevelDiv();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ; ?></span>
            </div><br/><br/>
            
            <div class="form-group" id='subject-type-div' style='display:none'>
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacysubjecttypeid">Subject Type: </label>
               <span><?= Html::dropDownList('subject_type_field',  null, ArrayHelper::map(LegacySubjectType::find()->all(), 'legacysubjecttypeid', 'name'), ['prompt' => 'Select..', 'id' => 'subject_type', 'onchange' => 'toggleSubjectTypeDiv();PrepareSubjectListing(event)', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ; ?></span>
            </div><br/><br/>
            
            <div class="form-group" id='subject-div' style='display:none'>
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacysubjectid">Subject: </label>
               <span><?= Html::dropDownList('subject_field', null, [], ['prompt' => 'Select..', 'id' => 'subject', 'onchange' => 'toggleSubjectDiv();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9", "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ; ?></span>
            </div><br/><br/>
            
            <div class="form-group" id='year-div' style='display:none'>
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacyyearid">Year: </label>
               <span><?= Html::dropDownList('year_field',  null, ArrayHelper::map(LegacyYear::find()->all(), 'legacyyearid', 'name'), ['prompt' => 'Select..', 'id' => 'year', 'onchange' => 'toggleYearDiv();PrepareTermListing(event)', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ; ?></span>
            </div><br/><br/>
            
            <div class="form-group" id='term-div' style='display:none'>
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="legacytermid">Term: </label>
               <span><?= Html::dropDownList('term_field', null, [], ['prompt' => 'Select..', 'id' => 'term', 'onchange' => 'toggleTermDiv();', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ; ?></span>
            </div><br/><br/>
        </div>

        <div class="box-footer pull-right" id='submit-button' style='display:none'>
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?= Html::a(' Cancel', ['batch/index'],  ['class' => 'btn btn-danger'] );?>
        </div>
    <?php ActiveForm::end(); ?>   
</div>