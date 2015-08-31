<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Student Search';
$this->params['breadcrumbs'][] = ['label' => 'Student Search', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(
            [
                //'action' => Url::to(['student/search-applicant']),
            ]); ?>
        <div class="body-content">
            <div class="row">
                <div class="col-lg-2">
                    <?= Html::label( 'Student No.',  'id'); ?>
                    <?= Html::input('text', 'id'); ?>
                </div>
                <div class="col-lg-2">
                    <?= Html::label( 'First Name',  'firstname'); ?>
                    <?= Html::input('text', 'firstname'); ?>
                </div>
                <div class="col-lg-2">
                   <?= Html::label('Last Name',  'lastname'); ?>
                    <?= Html::input('text', 'lastname'); ?>
                </div>
                <div class="col-lg-2">
                   <?= Html::label('Outside Email Address',  'email'); ?>
                    <?= Html::input('text', 'email'); ?>
                </div>
            </div>
        </div>
    <div class="form-group">
        <br/>
        <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if ($results) : ?>
        <h3><?= "Search results for: " . $info_string ?></h3>
        <?= $this->render('_results', [
            'dataProvider' => $results,
            'info_string' => $info_string,
        ]) ?>
    <?php endif; ?>

</div>