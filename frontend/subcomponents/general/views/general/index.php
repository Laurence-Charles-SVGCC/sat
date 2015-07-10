<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ApplicationPeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="General-default-index">
    <h1>General Home</h1>
    <p>
        
    </p>
    <p>
       <?= Html::a('Manage Divisions', ['division/index'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
