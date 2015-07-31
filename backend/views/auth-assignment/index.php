<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Employee;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authorization Assignments';
$this->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => ['rbac/index'] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Assign Role or Permission', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_name',
            
            [
                'attribute' => 'user_id',
                'label' => "User",
                'format' => 'text',
                'value' => function($model)
                {
                    $employee = Employee::findOne(['personid' => $model->user_id]);
                    return $employee ? $employee->firstname . " " . $employee->lastname : $model->user_id; 
                }
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Date Created',
                'format' => 'text',
                'value' => function($model)
                {
                    return $model->created_at ? date('Y-m-d', $model->created_at) : 'Not Set'; 
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
