<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Modify Billing Category";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Categories", "url" => ["index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php
        $form = ActiveForm::begin(["id" => "update-billing-category-form"]);
        ?>
        <div class="box-body">
            <?= $form->field($model, "name")->textInput(); ?>

            <?=
                $form->field($model, "billing_scope_id")
                    ->inline()
                    ->radioList($billingScopes);
            ?>

            <?=
                Html::submitButton(
                    "Save",
                    [
                        "id" => "update-billing-category-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>