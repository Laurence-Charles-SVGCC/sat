<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Add Billing Category";

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
        <?php $form = ActiveForm::begin(["id" => "create-billing-category-form"]); ?>
        <div class="box-body">
            <?= $form->field($model, "name")->textInput(); ?>

            <?=
                $form->field($model, "billing_scope_id")
                    ->inline()
                    ->radioList($billingScopes);
            ?>

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "create-billing-category-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>