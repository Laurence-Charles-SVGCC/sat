<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */

$this->title = $model->academicofferingid;
$this->params['breadcrumbs'][] = ['label' => 'Academic Offerings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-offering-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateAcademicOffering')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->academicofferingid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('deleteAcademicOffering')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->academicofferingid], [
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
            'academicofferingid',
            'programmecatalogid',
            'academicyearid',
            'applicationperiodid',
            'spaces',
            'appliable:boolean',
            'isactive:boolean',
            'isdeleted:boolean',
        ],
    ]) ?>

</div>
