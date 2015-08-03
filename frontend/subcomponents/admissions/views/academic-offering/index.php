<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicYear;
use frontend\models\ApplicationPeriod;
//use frontend;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AcademicOfferingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Offerings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-offering-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Academic Offering', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Programme',
                'format' => 'text',
                'value' => function($model){
                    $result = ProgrammeCatalog::findOne(['programmecatalogid' => $model->programmecatalogid]);
                    return $result->name;
                }
             ],
            [
                'label' => 'Academic Year',
                'format' => 'text',
                'value' => function($model){
                    $result = AcademicYear::findOne(['academicyearid' => $model->academicyearid]);
                    return $result->title;
                }
             ],
            [
                'label' => 'Application Period',
                'format' => 'text',
                'value' => function($model){
                    $result = ApplicationPeriod::findOne(['applicationperiodid' => $model->applicationperiodid]);
                    return $result->name;
                }
             ],
            'spaces',
            'interviewneeded:boolean',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
