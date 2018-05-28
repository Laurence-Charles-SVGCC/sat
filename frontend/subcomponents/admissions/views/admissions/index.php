<?php
    use yii\helpers\Html;

    $this->title = 'SVGCC Administrative Terminal';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Admissions Dashboard</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Select a task</h2>
                <?php if (Yii::$app->user->can('viewApplicationPeriod')): ?>
                    <?= Html::a('Manage Application Periods', ['application-period/index'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('viewAcademicOffering')): ?>
                    <?= Html::a('Manage Academic Offerings', ['academic-offering/index'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('viewApplicationStatus')): ?>
                    <?= Html::a('Manage Academic Status', ['application-status/index'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
