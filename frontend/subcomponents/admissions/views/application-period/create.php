<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationPeriod */

$this->title = 'Create Application Period';
$this->params['breadcrumbs'][] = ['label' => 'Application Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
