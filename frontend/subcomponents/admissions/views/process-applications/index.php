<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Applications Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <!-- Dashboard buttons -->
              <div class="box">
                <div class="box-body">
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($pending==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 3])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $pending ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Pending</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($shortlist==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 4])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $shortlist ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Shortlist</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($borderline==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 7])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $borderline ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Borderline</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($rejected==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 6])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $rejected ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Pre Interview<br/>Rejects</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($interviewoffer==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 8])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $interviewoffer ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Interviewees</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($conditionalofferreject==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 10])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $conditionalofferreject ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Post-Interview<br/>Rejects</strong>
                    </a>
                    
                    <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($offer==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 9])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $offer ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Offer</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
