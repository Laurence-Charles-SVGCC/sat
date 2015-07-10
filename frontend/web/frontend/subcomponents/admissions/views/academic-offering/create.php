<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AcademicOffering */

$this->title = 'Create Academic Offering';
$this->params['breadcrumbs'][] = ['label' => 'Academic Offerings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-offering-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
