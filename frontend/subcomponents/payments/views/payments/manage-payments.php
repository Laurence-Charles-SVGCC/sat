<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Search Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Dashboard buttons -->
      <div class="box">
       <?php if (Yii::$app->user->can('managePayments')): ?> 
        <div class="box-body">
            <a class="btn btn-app" href="<?= Url::to(['payments/search-applicant'])?>">
                <i class="fa fa-cart-plus"></i> Applicants
             </a>
            
            <a class="btn btn-app" href="<?= Url::to(['payments/search-student'])?>">
                <i class="fa fa-check"></i> Students
             </a>
            
            <a class="btn btn-app" href="<?= Url::to(['payments/search-employee'])?>">
                <i class="fa fa-question"></i> Employees
             </a>
            
        </div>
       <?php endif; ?>
      </div>
</div>
