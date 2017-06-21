<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $this->title = 'Applicant Deferral';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username, 'unrestricted' => $unrestricted])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'applicantusername' => $user->username]);?>" title="Applicant Profile">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Deferral Of Un-enrolled Successful</span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <div class="form-group">
               <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="details">Deferral Details:</label>
               <?= $form->field($applicant_deferral, 'details')->label('')->textArea(['rows' => '8', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]);?>
            </div>
        </div>
    
        <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile','search_status' => $search_status, 'applicantusername' => $user->username, 'unrestricted' => $unrestricted], ['class' => 'btn btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>