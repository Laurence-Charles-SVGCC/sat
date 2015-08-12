<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\grid\GridView;
//use yii\helpers\Url;
use frontend\models\Institution;

$this->title = 'Applicant Details';
$this->params['breadcrumbs'][] = ['label' => 'Applicant View', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Details for: <?= $username ?> </h2>
    <h3>Personal Details</h3>
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::hiddenInput('applicantid', $applicant->applicantid) ?>
    <?= Html::hiddenInput('username', $username) ?>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($applicant, 'title')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'firstname')->textInput(); ?>
        </div>
        <div class="col-lg-3">      
            <?= $form->field($applicant, 'middlename')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'lastname')->textInput(); ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($applicant, 'gender')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'dateofbirth')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'nationality')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'placeofbirth')->textInput(); ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($applicant, 'religion')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'sponsorname')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'clubs')->textInput(); ?>
        </div>
    </div>
    <br/>
    <div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'maritalstatus')->textInput(); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($applicant, 'otherinterests')->textInput(); ?>
        </div>
    </div>
    <br/><br/><br/>
    <h3>Institutional Attendance Details</h3>
    <?php foreach($institutions as $inst): ?>
    <?php $in = Institution::findone(['institutionid' => $inst->institutionid, 'isdeleted' => 0]); ?>
        <div class="row">
            <div class="col-lg-2">
                <?= $form->field($in, 'name')->textInput(); ?>
            </div>
            <div class="col-lg-2">
                
                <?= $form->field($in, 'formername')->textInput(); ?>
            </div>
            <div class="col-lg-2">
                
                <?= $form->field($inst, 'startdate')->textInput(); ?>
            </div>
            <div class="col-lg-2">
                
                <?= $form->field($inst, 'enddate')->textInput(); ?>
            </div>
            <div class="col-lg-2">
                <?php //$grad = $value['hasgraduated'] ? 'Yes' : 'No' ?>
                
                <?= $form->field($inst, 'hasgraduated')->textInput(); ?>
            </div>          
      </div>
    <?php endforeach; ?>
    
    <br/>
        <?php if (Yii::$app->user->can('editApplicantPersonal')): ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>
    <?php ActiveForm::end(); ?>
    
</div>