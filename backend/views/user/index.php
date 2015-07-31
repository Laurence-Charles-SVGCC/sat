<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\PersonType;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'personid',
            'username',
            [
                'attribute' => 'persontypeid',
                'label' => 'User Type',
                'format' => 'text',
                'value' => function($model)
                {
                    $type = PersonType::findOne(['persontypeid' => $model->persontypeid]);
                    return $type ? $type->persontype : 'Undefined'; 
                }
            ],
            'datecreated',
            'dateupdated',
            'isactive:boolean',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
