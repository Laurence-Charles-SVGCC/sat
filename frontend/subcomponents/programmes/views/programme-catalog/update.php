<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProgrammeCatalog */

$this->title = 'Update Programme: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Programme Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->programmecatalogid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="programme-catalog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
