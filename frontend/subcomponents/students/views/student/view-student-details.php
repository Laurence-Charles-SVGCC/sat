<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
//use yii\grid\GridView;
//use yii\helpers\Url;
use frontend\models\Institution;

$this->title = 'Student Details';
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Details for: <?= $username ?> </h2>
    <?php if ($student): ?>
        <h3>Personal Details</h3>
        <div class="row">
            <div class="col-lg-3">
                <?= "<strong>Title: </strong>" . $student->title ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>First Name: </strong>" . $student->firstname ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Middle Name(s): </strong>" . $student->middlename ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Last Name: </strong>" . $student->lastname ?>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-3">
                <?= "<strong>Gender: </strong>" . $student->gender ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Date of Birth: </strong>" . $student->dateofbirth ?>
            </div>
            <div class="col-lg-3">
                <?php //"<strong>Nationality: </strong>" . $student->nationality ?>
            </div>
            <div class="col-lg-3">
                <?php //"<strong>Place of Birth: </strong>" . $student->placeofbirth ?>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-3">
                <?php //"<strong>Religion: </strong>" . $student->religion ?>
            </div>
            <div class="col-lg-3">
                <?php //"<strong>Sponsor: </strong>" . $student->sponsorname ?>
            </div>
            <div class="col-lg-3">
                <?php //"<strong>Clubs: </strong>" . $student->clubs ?>
            </div>
        </div>
        <br/>
        <div>
            <div class="col-lg-3">
                <?php //"<strong>Marital Status: </strong>" . $student->maritalstatus ?>
            </div>
            <div class="col-lg-3">
                <?php //"<strong>other Interests: </strong>" . $student->otherinterests ?>
            </div>
        </div>
        <br/>
        <h3>Contact</h3>
        <div class="row">
            <div class="col-lg-3">
                <?= "<strong>Home Phone: </strong>" . ($phone ? $phone->homephone : "N/A") ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Cell Phone: </strong>" . ($phone ? $phone->cellphone : "N/A") ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Work Phone: </strong>" . ($phone ? $phone->workphone : "N/A") ?>
            </div>
            <div class="col-lg-3">
                <?= "<strong>Email: </strong>" . ($email ? $email->email : "N/A") ?>
            </div>
        </div>
        <h3>Relation Contact</h3>
        <?php foreach($relations as $relation): ?>
            <?php if ($relation->firstname != ''): ?> 
                <div class="row">
                    <div class="col-lg-2">
                        <?= "<strong>First Name: </strong>" . $relation->firstname ?>
                    </div>
                    <div class="col-lg-2">
                        <?= "<strong>Last Name: </strong>" .  $relation->lastname ?>
                    </div>
                    <div class="col-lg-2">
                        <?= "<strong>Home Phone: </strong>" .  $relation->homephone ?>
                    </div>
                    <div class="col-lg-2">
                        <?= "<strong>Cell Phone: </strong>" .  $relation->cellphone ?>
                    </div>
                    <div class="col-lg-2">
                        <?= "<strong>Work Phone: </strong>" .  $relation->workphone ?>
                    </div>     
              </div>
        <br/>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <h3>Institutional Attendance Details</h3>
        <?php foreach($institutions as $inst): ?>
            <?php $in = Institution::findone(['institutionid' => $inst->institutionid, 'isdeleted' => 0]); ?>
            <div class="row">
                <div class="col-lg-2">
                    <?= "<strong>Name: </strong>" . $in->name ?>
                </div>
                <div class="col-lg-2">
                    <?= "<strong>Formerly: </strong>" .  $in->formername ?>
                </div>
                <div class="col-lg-2">
                    <?= "<strong>From: </strong>" .  $inst->startdate ?>
                </div>
                <div class="col-lg-2">
                    <?= "<strong>To: </strong>" .  $inst->enddate ?>
                </div>
                <div class="col-lg-2">
                    <?php $grad = $inst->hasgraduated ? 'Yes' : 'No' ?>
                    <?= "<strong>Graduated: </strong>" .  $grad ?>
                </div>          
          </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>