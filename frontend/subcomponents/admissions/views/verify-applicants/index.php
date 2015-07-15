<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Verify Applicants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'centre_name',
                'format' => 'text',
                'label' => 'Centre Name'
            ],
            [
                'attribute' => 'status',
                'format' => 'text',
                'label' => 'Status'
            ],
            [
                'attribute' => 'applicants_verified',
                'format' => 'text',
                'label' => 'Applicants Verified'
            ],
            [
                'attribute' => 'total_received',
                'format' => 'text',
                'label' => 'Total Received Applicants'
            ],
            [
                'attribute' => 'percentage_completed',
                'format' => 'text',
                'label' => 'Percentage Completed'
            ],
        ],
    ]); ?>

</div>