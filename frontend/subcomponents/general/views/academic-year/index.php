<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AcademicYearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Years';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-year-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if (Yii::$app->user->can('createAcademicYear')): ?>
            <?= Html::a('Create Academic Year', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'iscurrent:boolean',
            'startdate',
            'enddate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
