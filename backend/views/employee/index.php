<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'employeeid',
            'personid',
            'employeetitleid',
            'title',
            'firstname',
            // 'middlename',
            // 'lastname',
            // 'gender',
            // 'dateofbirth',
            // 'maritalstatus',
            // 'nationality',
            // 'religion',
            // 'placeofbirth',
            // 'photopath',
            // 'nationalidnumber',
            // 'nationalinsurancenumber',
            // 'inlandrevenuenumber',
            // 'signaturepath',
            // 'isactive:boolean',
            // 'isdeleted:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
