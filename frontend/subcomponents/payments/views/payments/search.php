<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use frontend\models\Division;
use frontend\models\AcademicYear;
use frontend\models\ProgrammeCatalog;

$type = ucfirst($type);
$this->title = $type . ' Search';
$this->params['breadcrumbs'][] = ['label' => 'Manage Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
        <div class="body-content">
            <div class="row">
                <div class="col-lg-4">
                    <?= Html::label( $type . ' ID',  'text'); ?>
                    <?= Html::input('text', 'id'); ?>
                </div>
                <div class="col-lg-4">
                    <?= Html::label( 'First Name',  'firstname'); ?>
                    <?= Html::input('text', 'firstname'); ?>
                </div>
                <div class="col-lg-4">
                   <?= Html::label('Last Name',  'firstname'); ?>
                    <?= Html::input('text', 'lastname'); ?>
                </div>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if ($results) : ?>
        <?= $this->render('_results', [
            'dataProvider' => $results,
        ]) ?>
    <?php endif; ?>

</div>