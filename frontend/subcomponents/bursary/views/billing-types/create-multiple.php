<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Add Billing Type(s)";

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
        <?php $form = ActiveForm::begin(["id" => "create-multiple-billing-type-form"]); ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Save</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Divisions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($billingTypeBatchForms as $key => $billingTypeBatchForm) :
                ?>
                    <tr>
                        <td width="5%">
                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]is_active"
                                )
                                    ->checkbox(['label' => false]);
                            ?>
                        </td>

                        <td width='55%'>
                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]name"
                                )
                                    ->label(false)
                                    ->textInput();
                            ?>
                        </td>

                        <td width='20%'>
                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]billing_category_id"
                                )
                                    ->label(false)
                                    ->radioList($billingCategories);
                            ?>
                        </td>

                        <td width='20%'>
                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]dasgs_administered"
                                )
                                    ->checkbox(); ?>

                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]dtve_administered"
                                )
                                    ->checkbox();
                            ?>

                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]dte_administered"
                                )
                                    ->checkbox();
                            ?>

                            <?=
                                $form->field(
                                    $billingTypeBatchForm,
                                    "[{$key}]dne_administered"
                                )
                                    ->checkbox();
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?=
            Html::submitButton(
                "Add",
                [
                    "id" => "create-multiple-billing-type-submit-button",
                    "class" => "btn btn-success pull-right"
                ]
            );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>