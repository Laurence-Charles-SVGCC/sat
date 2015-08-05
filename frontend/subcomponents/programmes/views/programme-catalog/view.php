<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProgrammeCatalog */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Programme Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programme-catalog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('updateProgramme')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->programmecatalogid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('deleteProgramme')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->programmecatalogid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'specialisation',
            'examinationbodyid',
            'qualificationtypeid',
            'departmentid',
            'creationdate',
            'duration',
            
        ],
    ]) ?>

</div>
