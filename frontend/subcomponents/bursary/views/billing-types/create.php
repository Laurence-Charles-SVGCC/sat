<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Add Billing Type";

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
        <?php $form = ActiveForm::begin(["id" => "create-billing-type-form"]); ?>
        <div class="box-body">
            <?= $form->field($model, "name")->textInput(); ?>

            <?=
                $form->field($model, "billing_category_id")
                    ->label("Billing Cateogry")
                    ->inline()
                    ->radioList($billingCategories);
            ?><br />

            <?= $form->field($model, "dasgs_administered")->checkbox(); ?><br />
            <?= $form->field($model, "dtve_administered")->checkbox(); ?><br />
            <?= $form->field($model, "dte_administered")->checkbox(); ?><br />
            <?= $form->field($model, "dne_administered")->checkbox(); ?><br />

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "create-billing-type-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>