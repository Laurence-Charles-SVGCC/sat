<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Review Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>
            <h4>Click one of the buttons below or use the dropdown to filter</h4>
            <!-- Dashboard buttons -->
              <div class="box">

                <div class="box-body">
                    <?php foreach ($appstatuses as $appstatus): ?>
                        <a style="width:12%; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['review-applications/view-by-status', 'division_id' => $division_id, 
                        'application_status' => $appstatus->applicationstatusid])?>">
                            <span class="badge bg-green" style="font-size:16px;"><?= $statuscounts[$appstatus->applicationstatusid] ?></span>
                            <i class="fa fa-cart-plus"></i><strong> <?= $appstatus->name ?></strong>
                         </a>
                    <?php endforeach; ?>

                    <a style="width:12%; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['review-applications/view-referred-to', 'division_id' => $division_id])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $referred_to_count ?></span>
                        <i class="fa fa-cart-plus"></i><strong> Referred To</strong>
                     </a>

                    <a style="width:12%; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['review-applications/view-all', 'division_id' => $division_id])?>">
                        <span class="badge bg-green" style="font-size:16px;"><?= $total_count ?></span>
                        <i class="fa fa-cart-plus"></i> <strong>All</strong>
                     </a>
        
                </div>
            </div>
        </div>
    </div>
</div>
