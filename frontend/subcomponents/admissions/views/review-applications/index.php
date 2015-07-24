<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\ApplicationStatus;
use yii\widgets\ActiveForm;

$this->title = 'Review Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Dashboard buttons -->
      <div class="box">
        
        <div class="box-body">
            <?php ActiveForm::begin(); ?>
            <?= Html::label('Select Criteria', 'applicationstatusid'); ?>
            <?= Html::dropDownList('application_status_id', NULL,
                ArrayHelper::map(ApplicationStatus::find()->all(), 'applicationstatusid', 'name'))  ; ?>
            <?= Html::submitButton('Query', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
            <!--<a class="btn btn-app" href="<?= "a" //Url::to(['verify-applicants/view-pending', 'divisionid' => $division_id])?>">
                <span class="badge bg-green"><?= "a" //$pending ?></span>
                <i class="fa fa-cart-plus"></i> Pending
             </a>
            
            <a class="btn btn-app" href="<?= "a" //Url::to(['verify-applicants/view-verified', 'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                <span class="badge bg-green"><?= "a" //$verified ?></span>
                <i class="fa fa-check"></i> Verified
             </a>
            
            <a class="btn btn-app" href="<?= "a" //Url::to(['verify-applicants/view-queried', 'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                <span class="badge bg-green"><?= "a" //$queried ?></span>
                <i class="fa fa-question"></i> Queries
             </a>
            
            <a class="btn btn-app" href="<?= "a" //Url::to(['verify-applicants/view-all', 'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                <span class="badge bg-green"><?= "a" //$total ?></span>
                <i class="fa fa-users"></i> All
             </a>-->
        </div>
      </div>
</div>
