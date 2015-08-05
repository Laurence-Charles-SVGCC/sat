<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationPeriod */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-period-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateApplicationPeriod')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->applicationperiodid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('deleteApplicationPeriod')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->applicationperiodid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'applicationperiodid',
            'divisionid',
            'personid',
            'academicyearid',
            'name',
            'onsitestartdate',
            'onsiteenddate',
            'offsitestartdate',
            'offsiteenddate',
            'isactive:boolean',
            'isdeleted:boolean',
        ],
    ]) ?>

</div>
