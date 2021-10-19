<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Add {$applicationPeriodName} Application Submission Fee";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Application Fee Catalog", "url" => ["application-fees/view-fee-listing"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php
        $form = ActiveForm::begin(["id" => "add-application-submission-billing-charge-to-application-period-form"]); ?>
        <div class="box-body">
            <?= $form->field($model, "cost")->textInput(); ?>

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "add-application-submission-billing-charge-to-application-period-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>