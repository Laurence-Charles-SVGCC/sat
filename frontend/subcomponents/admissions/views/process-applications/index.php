<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;

    $this->title = 'Applications Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body">
        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_pending==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 3])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_pending ?></span>
            <i class="fa fa-cart-plus"></i><strong> Pending</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_shortlist==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 4])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_shortlist ?></span>
            <i class="fa fa-cart-plus"></i><strong> Shortlist</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_borderline==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 7])?>">
           <span class="badge bg-red" style="font-size:16px;"><?= $authorized_borderline ?></span>
            <i class="fa fa-cart-plus"></i><strong> Borderline</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_rejected==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 6])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_rejected ?></span>
            <i class="fa fa-cart-plus"></i><strong> Pre Interview<br/>Rejects</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_interviewoffer==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 8])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_interviewoffer ?></span>
            <i class="fa fa-cart-plus"></i><strong> Interviewees</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_conditionalofferreject==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 10])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_conditionalofferreject ?></span>
            <i class="fa fa-cart-plus"></i><strong> Post-Interview<br/>Rejects</strong>
        </a>

        <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px; <?php if($authorized_offer==0) echo 'pointer-events:none;cursor: default;opacity: 0.6;'?>"" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 9])?>">
            <span class="badge bg-red" style="font-size:16px;"><?= $authorized_offer ?></span>
            <i class="fa fa-cart-plus"></i><strong> Offer</strong>
        </a>

        <?php if (Yii::$app->user->can('System Administrator')): ?>
            <a style="width:12%; height:100px; margin-left:10%; margin-right:10%; margin-top:5%; margin-bottom:5%; font-size:16px;" class="btn btn-app" href="<?= Url::to(['process-applications/view-by-status', 'division_id' => $division_id, 'application_status' => 0])?>">
                <span class="badge bg-red" style="font-size:16px;"><?= $exceptions ?></span>
                <i class="fa fa-cart-plus"></i><strong> Exceptions</strong>
            </a>
        <?php endif;?>
    </div>
</div>