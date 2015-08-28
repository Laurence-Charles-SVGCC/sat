<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Dashboard buttons -->
      <div class="box">
        
       <?php if (Yii::$app->user->can('manageStudents')): ?>
        <div class="box-body">
            <a class="btn btn-app" href="<?= Url::to(['student/view-students', 
                'divisionid' => $dasgsid])?>">
                <i class="fa fa-cart-plus"></i> DASGS
             </a>
            
            <a class="btn btn-app" href="<?= Url::to(['student/view-students',
                'divisionid' => $dtveid])?>">
                <i class="fa fa-check"></i> DTVE
             </a>
            
            <a class="btn btn-app" href="<?= Url::to(['student/view-students',
                'divisionid' => 1])?>">
                <i class="fa fa-users"></i> All
             </a>
        </div>
       <?php endif; ?>
      </div>
    <!-- Button with count at top for future. add above i tag: <span class="badge bg-green">value</span>-->
</div>
