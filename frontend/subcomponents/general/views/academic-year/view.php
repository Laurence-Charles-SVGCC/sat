<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicYear */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Academic Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-year-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateAcademicYear')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->academicyearid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('deleteAcademicYear')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->academicyearid], [
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
            'title',
            'iscurrent:boolean',
            'startdate',
            'enddate',
        ],
    ]) ?>

</div>
