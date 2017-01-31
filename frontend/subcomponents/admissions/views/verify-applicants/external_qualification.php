<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use dosamigos\datepicker\DatePicker;
    
    use frontend\models\PostSecondaryQualification;
    
    if(PostSecondaryQualification::getPostSecondaryQualifications($user->personid) == true)
        $this->title = 'Edit External Qualification';
    else
        $this->title = 'Add External Qualification';
    
    $this->params['breadcrumbs'][] = ['label' => 'Applicant:' . $applicant->firstname . " " . $applicant->lastname, 
        'url' => ['view-applicant-qualifications', 'applicantid' => $applicant->personid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type]];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em;">
    <div class="box-header with-border">
        <span><?= $this->title;?></h2></span>
    </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">Name of Qualification*:</label>
               <?=$form->field($qualification, 'name')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
        
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="awardinginstitution">Awarding Institution:</label>
               <?=$form->field($qualification, 'awardinginstitution')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
        
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="yearawarded">Year Degree Awarded:</label>
               <?=$form->field($qualification, 'yearawarded')->label('')->textInput(["class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"])?>
           </div>
        </div>

        <div class="box-footer pull-right">
            <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            <?=Html::a(' Back', 
                                ['verify-applicants/view-applicant-qualifications', 'applicantid' => $applicant->personid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type], 
                                ['class' => 'btn btn-danger']);
            ?> 
        </div>
    <?php ActiveForm::end();?>
</div>