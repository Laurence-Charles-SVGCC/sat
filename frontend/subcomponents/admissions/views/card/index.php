<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cards ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>
            <!-- Dashboard buttons -->
              <div class="box">

               <?php if (Yii::$app->user->can('studentCard')): ?>
                <div class="box-body">
                    <a class="btn btn-app" href="<?= Url::to(['card/view-applicants', 
                        'divisionid' => $dasgsid])?>">
                        <i class="fa fa-cart-plus"></i> DASGS
                     </a>

                    <a class="btn btn-app" href="<?= Url::to(['card/view-applicants',
                        'divisionid' => $dtveid])?>">
                        <i class="fa fa-check"></i> DTVE
                     </a>

                    <a class="btn btn-app" href="<?= Url::to(['card/view-applicants',
                        'divisionid' => 1])?>">
                        <i class="fa fa-users"></i> All
                     </a>
                </div>
               <?php endif; ?>
              </div>
            <!-- Button with count at top for future. add above i tag: <span class="badge bg-green">value</span>-->
        </div>
    </div>
</div>
