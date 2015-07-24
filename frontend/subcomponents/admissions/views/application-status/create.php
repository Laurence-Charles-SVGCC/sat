<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ApplicationStatus */

$this->title = 'Create Application Status';
$this->params['breadcrumbs'][] = ['label' => 'Application Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
