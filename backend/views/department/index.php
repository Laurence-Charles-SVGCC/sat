<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Division;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'departmentid',
            'name',
            [
                'attribute' => 'divisionid',
                'label' => 'Division',
                'format' => 'text',
                'value' => function($model)
                {
                    $division = Division::findOne(['divisionid' => $model->divisionid]);
                    return $division ? $division->name : 'Undefined'; 
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
