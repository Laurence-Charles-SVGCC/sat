<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\ApplicationStatus;
use yii\widgets\ActiveForm;

$this->title = 'Review Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verif-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Dashboard buttons -->
      <div class="box">
        
        <div class="box-body">
            <?php ActiveForm::begin(); ?>
            <?= Html::label('Select Criteria', 'applicationstatusid'); ?>
            <?= Html::dropDownList('application_status_id', NULL,
                ArrayHelper::map(ApplicationStatus::find()->all(), 'applicationstatusid', 'name'))  ; ?>
            <?php if (Yii::$app->user->can('reviewApplications')): ?>
                <?= Html::submitButton('Query', ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
            <?php ActiveForm::end(); ?>
        </div>
      </div>
</div>
