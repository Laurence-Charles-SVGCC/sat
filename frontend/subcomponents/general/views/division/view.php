<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Division */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateDivision')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->divisionid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('deleteDivision')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->divisionid], [
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
            'divisionid',
            'locationid',
            'name',
            'abbreviation',
            'phone',
            'email:email',
            //'isactive:boolean',
            //'isdeleted:boolean',
        ],
    ]) ?>

</div>
