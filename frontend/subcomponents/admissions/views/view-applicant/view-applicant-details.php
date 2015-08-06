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
    <div class="row">
        <div class="col-lg-3">
            <?= "<strong>Title: </strong>" . $info['title'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>First Name: </strong>" . $info['firstname'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Middle Name(s): </strong>" . $info['middlename'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Last Name: </strong>" . $info['lastname'] ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= "<strong>Gender: </strong>" . $info['gender'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Date of Birth: </strong>" . $info['dateofbirth'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Nationality: </strong>" . $info['nationality'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Place of Birth: </strong>" . $info['placeofbirth'] ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= "<strong>Religion: </strong>" . $info['religion'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Sponsor: </strong>" . $info['sponsor'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>Clubs: </strong>" . $info['clubs'] ?>
        </div>
    </div>
    <br/>
    <div>
        <div class="col-lg-3">
            <?= "<strong>Marital Status: </strong>" . $info['maritalstatus'] ?>
        </div>
        <div class="col-lg-3">
            <?= "<strong>other Interests: </strong>" . $info['otherinterests'] ?>
        </div>
    </div>
    <br/>
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
    
</div>