<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AcademicOfferingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Offerings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-offering-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Academic Offering', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'academicofferingid',
            'programmecatalogid',
            'academicyearid',
            'applicationperiodid',
            'spaces',
            // 'appliable:boolean',
            // 'isactive:boolean',
            // 'isdeleted:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
