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
                                            "<li style = 'list-style-type:none; margin-top:10px'>"
                                            . Html::a(
                                                "Revert fee to {$charge}",
                                                Url::to(
                                                    [
                                                        "revert-billing-charge-from-dashboard",
                                                        "fromBillingChargeId" => $row["billingChargeId"],
                                                        "toBillingChargeId" => $key
                                                    ]
                                                ),
                                                [
                                                    "class" => "btn btn-primary",
                                                    "style" => "border:0px; width:150px"
                                                ]
                                            )
                                            . "</li>";
                                    }
                                }

                                $anchors .=
                                    "<li style = 'list-style-type:none; margin-top:10px'>"
                                    . Html::a(
                                        "Create new fee",
                                        Url::to(
                                            [
                                                "update-billing-charge-cost-from-dashboard",
                                                "billingChargeId" => $row["billingChargeId"]
                                            ]
                                        ),
                                        [
                                            "class" => "btn btn-primary",
                                            "style" => "border:0px; width:150px"
                                        ]
                                    )
                                    . "</li>";

                                return "<ul>"
                                    . "<li style = 'list-style-type:none; margin-top:10px'>"
                                    . Html::a(
                                        "<strong>Currently - $ {$row['cost']}</strong>",
                                        null,
                                        [
                                            "class" => "btn btn-default",
                                            "disabled" => true,
                                            "style" => "border:0px; width:150px"
                                        ]
                                    )
                                    . "</li>"
                                    . $anchors;
                            }
                        ],
                        [
                            "format" => "raw",
                            "label" => "Payable On Enrollment",
                            "value" => function ($row) {
                                if ($row["payable_on_enrollment"] == true) {
                                    return Html::a(
                                        "<strong>Yes</strong>",
                                        null,
                                        [
                                            "class" => "btn btn-default",
                                            "disabled" => true
                                        ]
                                    )
                                        . Html::a(
                                            "Change to 'No'",
                                            Url::to(
                                                [
                                                    "update-payable-status",
                                                    "billingChargeId" => $row["billingChargeId"],
                                                    "input" => 0
                                                ]
                                            ),
                                            ["class" => "btn btn-warning"]
                                        );
                                } else {
                                    return Html::a(
                                        "<strong>No</strong>",
                                        null,
                                        [
                                            "class" => "btn btn-default",
                                            "disabled" => true
                                        ]
                                    )
                                        . Html::a(
                                            "Change to 'Yes'",
                                            Url::to(
                                                [
                                                    "update-payable-status",
                                                    "billingChargeId" => $row["billingChargeId"],
                                                    "input" => 1
                                                ]
                                            ),
                                            ["class" => "btn btn-success"]
                                        );
                                }
                            }
                        ],
                    ]
                ]
            );
        ?>
    </div>
</div>