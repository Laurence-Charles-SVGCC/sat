<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles and Permissions';
$this->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => ['rbac/index'] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
        
    </p>

    <h1><?= Html::encode('Roles'), "  ", Html::a('Create Role', ['create', 'type'=>1], ['class' => 'btn btn-success']) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $roleDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            'rule_name',
            'data:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
    <h1><?= Html::encode('Permissions'), "  ", Html::a('Create Permission', ['create', 'type'=>2], ['class' => 'btn btn-success'])?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $permissionDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            'rule_name',
            'data:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
