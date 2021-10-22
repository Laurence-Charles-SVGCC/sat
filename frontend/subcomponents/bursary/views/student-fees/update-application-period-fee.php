<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Update {$feeName}";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Programme Catalog", "url" => ["student-fees/view-fee-listing"]];

$this->params["breadcrumbs"][] =
    [
        "label" => "{$applicationPeriodTitle}",
        "url" => [
            "student-fees/view-application-period-student-fee",
            "applicationPeriodId" => $applicationPeriodId
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php $form = ActiveForm::begin(["id" => "update-application-period-fee-form"]); ?>
        <div class="box-body">
            <?= $form->field($model, "cost")->textInput(); ?>

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "update-application-period-fee-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>