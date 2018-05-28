<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;

    if (strcasecmp($centre_name, "External") == 0)
        $this->title = 'Details of: Applicants With External Qualifications';
    else
        $this->title = 'Details of: ' . $centre_name;
    
    $this->params['breadcrumbs'][] = ['label' => 'Examination Centre Listing', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body">
         <!-- Dashboard buttons -->
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