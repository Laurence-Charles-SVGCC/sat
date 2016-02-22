<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    /* @var $this yii\web\View */
    /* @var $searchModel frontend\models\CsecCentreSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    if (strcasecmp($centre_name, "External") == 0)
        $this->title = 'Details of: Applicants With External Qualifications';
    else
        $this->title = 'Details of: ' . $centre_name;
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
                <?php if (Yii::$app->user->can('verifyApplicants')): ?>
                    <div class="box-body">
                        <a style="width:10%; margin-left:15%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['verify-applicants/view-pending', 
                            'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                            <span class="badge bg-green" style="font-size:16px;"><?= $pending ?></span>
                            <i class="fa fa-cart-plus"></i> <strong>Pending</strong>
                         </a>

                        <a style="width:10%; margin-right:10%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['verify-applicants/view-verified',
                            'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                            <span class="badge bg-green" style="font-size:16px;"><?= $verified ?></span>
                            <i class="fa fa-check"></i> <strong>Verified</strong>
                         </a>

                        <a style="width:10%; margin-right:10%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['verify-applicants/view-queried',
                            'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                            <span class="badge bg-green" style="font-size:16px;"><?= $queried ?></span>
                            <i class="fa fa-question"></i> <strong>Queries</strong>
                         </a>

                        <a style="width:10%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['verify-applicants/view-all',
                            'cseccentreid' => $centre_id, 'centrename' => $centre_name])?>">
                            <span class="badge bg-green" style="font-size:16px;"><?= $total ?></span>
                            <i class="fa fa-users"></i> <strong>All</strong>
                         </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
