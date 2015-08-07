<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\Division;
use frontend\models\Employee;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ApplicationPeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Application Periods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-period-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if (Yii::$app->user->can('createApplicationPeriod')): ?>
            <?= Html::a('Create Application Period', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'divisionid',
                'value' => function ($model)
                    {
                        $division = Division::findOne(['divisionid' => $model->divisionid]);
                        return $division ? $division->abbreviation : $model->divisionid;
                    }
            ],
            [
                'attribute' => 'personid',
                'label' => 'Creator',
                'value' => function ($model)
                    {
                        $employee = Employee::findOne(['personid' => $model->personid]);
                        return $employee ? $employee->firstname . ' ' . $employee->lastname : $model->personid;
                    }
            ],
            'academicyearid',
            'name',
            'onsitestartdate',
            'onsiteenddate',
            'offsitestartdate',
            'offsiteenddate',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
