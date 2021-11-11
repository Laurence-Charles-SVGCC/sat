<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Add Programme Fee";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Programme Catalog", "url" => ["student-fees/view-fee-listing"]];

$this->params["breadcrumbs"][] =
    [
        "label" => "{$programmeTitle}",
        "url" => [
            "student-fees/view-academic-offering-fee-listing",
            "applicationPeriodId" => $applicationPeriodId,
            "academicOfferingId" => $academicOfferingId
        ]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php
        $form = ActiveForm::begin(["id" => "add-fee-to-academic-offering-form"]); ?>
        <div class="box-body">
            <?=
                $form->field($model, 'billing_type_id')
                    ->dropDownList(
                        $billingTypes,
                        [
                            'class' => 'form-control',
                            'prompt' => 'Select fee...'
                        ]
                    );
            ?>

            <?=
                $form->field($model, "payable_on_enrollment")
                    ->dropDownList(
                        [0 => "No", 1 => "Yes"],
                        ["prompt" => "Select ..."]
                    );
            ?>

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