<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div id="add-student-fees-form" class="panel panel-default" <?= $displayForm ?>>
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Enter New Fees</span>
        </h3>
    </div>
    <div class="panel-body">
        <?php
        $form =
            ActiveForm::begin(["id" => "add-student-fee-billing-charge-form"]);
        ?>

        <table class="table table-striped">
            <tr>
                <th></th>
                <th>Fee</th>
                <th>If fee is programmme specific</th>
                <th>Payable On Enrollment</th>
                <th>Cost</th>
            </tr>


            <?php foreach ($forms as $key => $value) : ?>
                <tr id=<?= $key; ?>>
                    <td width="5%">
                        <?= $key + 1; ?>
                    </td>

                    <td width="30%">
                        <?=
                            $form->field($forms[$key], "[{$key}]billing_type_id")
                                ->label(false)
                                ->dropDownList(
                                    $billingTypes,
                                    ["prompt" => "Select fee..."]
                                );
                        ?>
                    </td>

                    <td width='40%'>
                        <?=
                            $form->field($forms[$key], "[{$key}]academic_offering_id")
                                ->label(false)
                                ->dropDownList(
                                    $programmes,
                                    ["prompt" => "Select programme..."]
                                );
                        ?>
                    </td>

                    <td width='10%'>
                        <?=
                            $form->field($forms[$key], "[{$key}]payable_on_enrollment")
                                ->label(false)
                                ->dropDownList(
                                    [0 => "No", 1 => "Yes"],
                                    ["prompt" => "Select ..."]
                                );
                        ?>
                    </td>

                    <td width='15%'>
                        <?=
                            $form->field($forms[$key], "[{$key}]cost")
                                ->label(false)
                                ->textInput();
                        ?>
                    </td>


                </tr>
            <?php endforeach; ?>
        </table>

        <?=
            Html::submitButton(
                "Save",
                [
                    "id" => "add-student-fee-billing-charge-submit-button",
                    "class" => "btn btn-success pull-right"
                ]
            );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>