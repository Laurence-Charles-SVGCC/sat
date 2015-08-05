<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Details of: ' . $centre_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Dashboard buttons -->
      <div class="box">
        <?php if (Yii::$app->user->can('verifyApplicants')): ?>
            <div class="box-body">
                <a class="btn btn-app" href="<?= Url::to(['verify-applicants/view-pending', 
                    'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                    <span class="badge bg-green"><?= $pending ?></span>
                    <i class="fa fa-cart-plus"></i> Pending
                 </a>

                <a class="btn btn-app" href="<?= Url::to(['verify-applicants/view-verified',
                    'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                    <span class="badge bg-green"><?= $verified ?></span>
                    <i class="fa fa-check"></i> Verified
                 </a>

                <a class="btn btn-app" href="<?= Url::to(['verify-applicants/view-queried',
                    'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                    <span class="badge bg-green"><?= $queried ?></span>
                    <i class="fa fa-question"></i> Queries
                 </a>

                <a class="btn btn-app" href="<?= Url::to(['verify-applicants/view-all',
                    'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                    <span class="badge bg-green"><?= $total ?></span>
                    <i class="fa fa-users"></i> All
                 </a>
            </div>
          <?php endif; ?>
      </div>
</div>
