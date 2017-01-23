<?php
    use yii\widgets\Breadcrumbs;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\bootstrap\ActiveForm;
    use yii\bootstrap\ActiveField;
    
    $this->title = 'Edit Extracurricular Activity';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status]);?>" title="Find Applicant">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="nationalsports">National Sports:</label>
                <?= $form->field($applicant, 'nationalsports')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="othersports">Recreational Sports:</label>
                <?= $form->field($applicant, 'othersports')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="clubs">Club Participation:</label>
                <?= $form->field($applicant, 'clubs')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div><br/>
            
            <div class="form-group">
                <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="otherinterests">Other Interests:</label>
                <?= $form->field($applicant, 'otherinterests')->label('')->textArea(['rows' => '5'], ['class'=> 'no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9']) ?>
            </div>
        </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile',  'search_status' => $search_status,  'applicantusername' => $user->username], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>