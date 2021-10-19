<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Add Billing Scope";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Scopes", "url" => ["index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php $form = ActiveForm::begin(["id" => "create-billing-scope-form"]); ?>
        <div class="box-body">
            <?= $form->field($model, "name")->textInput(); ?>

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "create-billing-scope-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>