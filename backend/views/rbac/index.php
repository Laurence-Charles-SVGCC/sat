<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Roles Based Access Control';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Manage Roles and Permissions', ['auth-item/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Manage Authorization Rules', ['auth-rule/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Assign Children', ['auth-item-child/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Assign Roles', ['auth-assignment/index'], ['class' => 'btn btn-success']) ?>
    </p>

</div>