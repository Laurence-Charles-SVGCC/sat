<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CAPE Subject Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programme-catalog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateCapeSubject')): ?>
            <?= Html::a('Add CAPE Subject', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Name',
                'format' => 'html',
                'value' => function($row)
                {
                    return Html::a($row['name'], ['view', 'id' => $row['subjectid']]);
        
                }
            ],
            'examinationbodyid',
            'isactive:boolean',
        ],
    ]); ?>

</div>
