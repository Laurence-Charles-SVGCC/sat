<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentre */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Csec Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csec-centre-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cseccentreid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cseccentreid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cseccentreid',
            'name',
            'cseccode',
            'isactive:boolean',
            'isdeleted:boolean',
        ],
    ]) ?>

</div>
