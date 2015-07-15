<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CsecCentre */

$this->title = 'Create CSEC Centre';
$this->params['breadcrumbs'][] = ['label' => 'CSEC Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csec-centre-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
