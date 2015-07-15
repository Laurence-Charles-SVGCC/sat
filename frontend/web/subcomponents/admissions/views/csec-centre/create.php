<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentre */

$this->title = 'Create Csec Centre';
$this->params['breadcrumbs'][] = ['label' => 'Csec Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csec-centre-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
