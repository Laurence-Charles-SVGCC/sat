<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Review Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <h4>Click one of the buttons below or use the dropdown to filter</h4>
    <!-- Dashboard buttons -->
      <div class="box">
        
        <div class="box-body">
            <?php foreach ($appstatuses as $appstatus): ?>
                <a class="btn btn-app" href="<?= Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                'application_status' => $appstatus->applicationstatusid])?>">
                    <span class="badge bg-green"><?= $statuscounts[$appstatus->applicationstatusid] ?></span>
                    <i class="fa fa-cart-plus"></i> <?= $appstatus->name ?>
                 </a>
            <?php endforeach; ?>
            
            <a class="btn btn-app" href="<?= Url::to(['review-applications/view-referred-to', 'division_id' => $division_id])?>">
                <span class="badge bg-green"><?= $referred_to_count ?></span>
                <i class="fa fa-cart-plus"></i> Referred To
             </a>
            
            <a class="btn btn-app" href="<?= Url::to(['review-applications/view-all', 'division_id' => $division_id])?>">
                <span class="badge bg-green"><?= $total_count ?></span>
                <i class="fa fa-cart-plus"></i> All
             </a>
            
            <?php ActiveForm::begin(); ?>
            <?= Html::label('Select Criteria', 'applicationstatusid'); ?>
            <?= Html::dropDownList('application_status_id', NULL,
                ArrayHelper::map($appstatuses, 'applicationstatusid', 'name'))  ; ?>
            <?php if (Yii::$app->user->can('reviewApplications')): ?>
                <?= Html::submitButton('Query', ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
            <?php ActiveForm::end(); ?>
        </div>
      </div>
</div>
