<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default" <?= $displayExistingFees ?>>
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Existing Fees</span>
            <span class="pull-right">
                <button id="add-student-fees-form-toggle" class="btn btn-success" type="button" onClick="showFeeForm()">
                    Add Fees
                </button>
            </span><br /><br />
        </h3>
    </div>
    <div class="panel-body">
        <?=
            GridView::widget(
                [
                    "dataProvider" => $dataProvider,
                    "columns" => [
                        [
                            "attribute" => "billingTypeName",
                            "format" => "text",
                            "label" => "Type"
                        ],
                        [
                            "attribute" => "programme",
                            "format" => "text",
                            "label" => "Programme"
                        ],
                        [
                            "format" => "raw",
                            "label" => "Cost",
                            "value" => function ($row) {
                                $anchors = "";
                                if (!empty($row["pastBillingChargeInformation"])) {
                                    foreach ($row["pastBillingChargeInformation"] as $key => $charge) {
                                        $anchors .=
                                            "<li>"
                                            . Html::a(
                                                "Revert fee to {$charge}",
                                                Url::to(
                                                    [
                                                        "revert-billing-charge-from-dashboard",
                                                        "fromBillingChargeId" => $row["billingChargeId"],
                                                        "toBillingChargeId" => $key
                                                    ]
                                                )
                                            )
                                            . "</li>";
                                    }
                                }

                                $anchors .=
                                    "<li>"
                                    . Html::a(
                                        "Create new fee",
                                        Url::to(
                                            [
                                                "update-billing-charge-cost-from-dashboard",
                                                "billingChargeId" => $row["billingChargeId"]
                                            ]
                                        )
                                    )
                                    . "</li>";

                                return "<div class='dropdown'>"
                                    . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                    .   $row['cost']
                                    .   "<span class='caret'></span>"
                                    . "</button>"
                                    . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                    .   $anchors
                                    . "</ul>"
                                    . "</div>";
                            }
                        ],
                        [
                            "format" => "raw",
                            "label" => "Payable On Enrollment",
                            "value" => function ($row) {
                                if ($row["payable_on_enrollment"] == true) {
                                    $label = "Yes";
                                    $options =
                                        "<li>"
                                        . Html::a(
                                            "Change to No",
                                            Url::to(
                                                [
                                                    "update-payable-status",
                                                    "billingChargeId" => $row["billingChargeId"],
                                                    "input" => 0
                                                ]
                                            )
                                        )
                                        . "</li>";
                                } else {
                                    $label = "No";
                                    $options =
                                        "<li>"
                                        . Html::a(
                                            "Change to Yes",
                                            Url::to(
                                                [
                                                    "update-payable-status",
                                                    "billingChargeId" => $row["billingChargeId"],
                                                    "input" => 1
                                                ]
                                            )
                                        )
                                        . "</li>";
                                }
                                return "<div class='dropdown'>"
                                    . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                    .   $label
                                    .   " <span class='caret'></span>"
                                    . "</button>"
                                    . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                    .   $options
                                    . "</ul>"
                                    . "</div>";
                            }
                        ],
                    ]
                ]
            );
        ?>
    </div>
</div>