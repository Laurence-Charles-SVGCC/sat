<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Applicant Details';
$this->params['breadcrumbs'][] = ['label' => 'Applicant View', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Details for: <?= $info['username'] ?> </h2>
    <h3>Personal Details</h3>
    <?php ActiveForm::begin(); ?>
    <?= Html::hiddenInput('applicantid', $info['applicantid']) ?>
    <?= Html::hiddenInput('username', $info['username']) ?>
    <div class="row">
        <div class="col-lg-3">
            <?= Html::label( 'Title',  'title'); ?>
            <?= Html::textInput('title', $info['title']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'First Name',  'firstname'); ?>
            <?= Html::textInput('firstname', $info['firstname']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Middle Name(s)',  'middlename'); ?>
            <?= Html::textInput('middlename', $info['middlename']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Last Name',  'lastname'); ?>
            <?= Html::textInput('lastname', $info['lastname']) ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= Html::label( 'Gender',  'gender'); ?>
            <?= Html::textInput('gender', $info['gender']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'date of Birth',  'dateofbirth'); ?>
            <?= Html::textInput('dateofbirth', $info['dateofbirth']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Nationality',  'nationality'); ?>
            <?= Html::textInput('nationality', $info['nationality']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Place of Birth',  'placeofbirth'); ?>
            <?= Html::textInput('placeofbirth', $info['placeofbirth']) ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= Html::label( 'Religion',  'religion'); ?>
            <?= Html::textInput('religion', $info['religion']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Sponsor',  'sponsorname'); ?>
            <?= Html::textInput('sponsorname', $info['sponsor']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Clubs',  'clubs'); ?>
            <?= Html::textInput('clubs', $info['clubs']) ?>
        </div>
    </div>
    <br/>
    <div>
        <div class="col-lg-3">
            <?= Html::label( 'Marital Status',  'maritalstatus'); ?>
            <?= Html::textInput('maritalstatus', $info['maritalstatus']) ?>
        </div>
        <div class="col-lg-3">
            <?= Html::label( 'Other Interests',  'otherinterests'); ?>
            <?= Html::textInput('otherinterests', $info['otherinterests']) ?>
        </div>
    </div>
    <br/><br/>
    <h3>Institutional Attendance Details</h3>
    <?php foreach($info['institution'] as $inst=>$value): ?>
        <div class="row">
            <div class="col-lg-2">
                <?= "<strong>Name: </strong>" . $value['name'] ?>
            </div>
            <div class="col-lg-2">
                <?= "<strong>Formerly: </strong>" .  $value['formername'] ?>
            </div>
            <div class="col-lg-2">
                <?= "<strong>From: </strong>" .  $value['startdate'] ?>
            </div>
            <div class="col-lg-2">
                <?= "<strong>To: </strong>" .  $value['enddate'] ?>
            </div>
            <div class="col-lg-2">
                <?php $grad = $value['hasgraduated'] ? 'Yes' : 'No' ?>
                <?= "<strong>Graduated: </strong>" .  $grad ?>
            </div>          
      </div>
    <?php endforeach; ?>
    
    <br/>
        <?php if (Yii::$app->user->can('editApplicantPersonal')): ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>
    <?php ActiveForm::end(); ?>
    
</div>