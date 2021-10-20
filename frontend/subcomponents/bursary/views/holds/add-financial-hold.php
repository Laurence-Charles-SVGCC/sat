<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Add Hold";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
    [
        "label" => "{$userFullname}",
        "url" => ["profiles/user-profile", "username" => $username]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <?php $form = ActiveForm::begin(["id" => "add-financial-hold-form"]); ?>
        <div class="box-body">
            <?=
                $form->field($hold, "studentregistrationid")
                    ->inline()
                    ->radioList($studentRegistrations);
            ?>

            <?=
                $form->field($hold, "holdtypeid")
                    ->inline()
                    ->radioList($holdTypes);
            ?>

            <?=
                $form->field($hold, "details")
                    ->textArea(["rows" => 5]);
            ?>

            <?=
                Html::submitButton(
                    "Add",
                    [
                        "id" => "add-financial-hold-submit-button",
                        "class" => "btn btn-success pull-right"
                    ]
                );
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>