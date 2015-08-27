<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Applicant Search';
$this->params['breadcrumbs'][] = ['label' => 'Applicant Search', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(
            [
                'action' => Url::to(['view-applicant/search-applicant']),
            ]); ?>
        <div class="body-content">
            <div class="row">
                <div class="col-lg-3">
                    <?= Html::label( 'Applicant ID',  'id'); ?>
                    <?= Html::input('text', 'id'); ?>
                </div>
                <div class="col-lg-3">
                    <?= Html::label( 'First Name',  'firstname'); ?>
                    <?= Html::input('text', 'firstname'); ?>
                </div>
                <div class="col-lg-3">
                   <?= Html::label('Last Name',  'lastname'); ?>
                    <?= Html::input('text', 'lastname'); ?>
                </div>
                <div class="col-lg-3">
                   <?= Html::label('Email Address',  'email'); ?>
                    <?= Html::input('text', 'email'); ?>
                </div>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if ($results) : ?>
        <h3><?= "Search results for: " . $info_string ?></h3>
        <?= $this->render('_results', [
            'dataProvider' => $results,
            'result_users' => $result_users,
            'info_string' => $info_string,
        ]) ?>
    <?php endif; ?>

</div>