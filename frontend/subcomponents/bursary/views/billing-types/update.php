<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Modify Billing Type";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Types", "url" => ["index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php
        $form = ActiveForm::begin(["id" => "update-billing-type-form"]);
        ?>
        <div class="box-body">
            <?= $form->field($model, "name")->textInput(); ?>

            <?=
                $form->field($model, "billing_category_id")
                    ->label("Billing Cateogry")
                    ->inline()
                    ->radioList($billingCategories);
            ?>

            <?=
                $form->field($model, "division_id")
                    ->label("Division")
                    ->inline()
                    ->radioList($divisions);
            ?>

            <?=
                Html::submitButton(
                    "Save",
                    [
                        "id" => "update-billing-type-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>