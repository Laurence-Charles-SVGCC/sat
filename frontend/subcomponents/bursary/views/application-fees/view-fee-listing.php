<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Application Fee Catalog";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "attribute" => "periodName",
                    "format" => "text",
                    "label" => "Application Period"
                ],
                [
                    "format" => "raw",
                    "label" => "Application Submission Fee",
                    "value" => function ($row) {
                        if (
                            $row["applicationSubmissionChargeCost"] == false
                        ) {
                            return Html::a(
                                "Add",
                                Url::to(
                                    [
                                        "add-application-submission-billing-charge-to-application-period",
                                        "applicationPeriodId" => $row["applicationPeriodId"]
                                    ]
                                ),
                                ["id" => "add-application-submission-billing-charge-button"]
                            );
                        } elseif (
                            $row["applicationSubmissionChargeCost"] == true
                            && $row["otherApplicationSubmissionChargeInformation"] == false
                        ) {
                            $anchors = "";

                            $anchors .=
                                "<li>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-submission-billing-charge",
                                            "billingChargeId" => $row["applicationSubmissionChargeId"]
                                        ]
                                    )
                                )
                                . "</li>";

                            return "<div class='dropdown'>"
                                . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                .   $row['applicationSubmissionChargeCost']
                                .   "<span class='caret'></span>"
                                . "</button>"
                                . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                .   $anchors
                                . "</ul>"
                                . "</div>";
                        } elseif (
                            $row["applicationSubmissionChargeCost"] == true
                            && $row["otherApplicationSubmissionChargeInformation"] == true
                        ) {
                            $anchors = "";
                            foreach ($row["otherApplicationSubmissionChargeInformation"] as $key => $charge) {
                                $anchors .=
                                    "<li>"
                                    . Html::a(
                                        "Revert fee to {$charge}",
                                        Url::to(
                                            [
                                                "revert-billing-charge",
                                                "fromBillingChargeId" => $row["applicationSubmissionChargeId"],
                                                "toBillingChargeId" => $key
                                            ]
                                        )
                                    )
                                    . "</li>";
                            }

                            $anchors .=
                                "<li>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-submission-billing-charge",
                                            "billingChargeId" => $row["applicationSubmissionChargeId"]
                                        ]
                                    )
                                )
                                . "</li>";

                            return "<div class='dropdown'>"
                                . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                .   $row['applicationSubmissionChargeCost']
                                .   "<span class='caret'></span>"
                                . "</button>"
                                . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                .   $anchors
                                . "</ul>"
                                . "</div>";
                        }
                    }
                ],

                [
                    "format" => "raw",
                    "label" => "Application Amendment Fee",
                    "value" => function ($row) {
                        if (
                            $row["applicationAmendmentChargeCost"] == false
                        ) {
                            return Html::a(
                                "Add",
                                Url::to(
                                    [
                                        "add-application-amendment-billing-charge-to-application-period",
                                        "applicationPeriodId" => $row["applicationPeriodId"]
                                    ]
                                ),
                                ["id" => "add-application-amendment-billing-charge-button"]
                            );
                        } elseif (
                            $row["applicationAmendmentChargeCost"] == true
                            && $row["otherApplicationAmendmentChargeInformation"] == false
                        ) {
                            $anchors = "";

                            $anchors .=
                                "<li>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-amendment-billing-charge",
                                            "billingChargeId" => $row["applicationAmendmentChargeId"]
                                        ]
                                    )
                                )
                                . "</li>";

                            return "<div class='dropdown'>"
                                . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                .   $row['applicationAmendmentChargeCost']
                                .   "<span class='caret'></span>"
                                . "</button>"
                                . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                .   $anchors
                                . "</ul>"
                                . "</div>";
                        } elseif (
                            $row["applicationAmendmentChargeCost"] == true
                            && $row["otherApplicationAmendmentChargeInformation"] == true
                        ) {
                            $anchors = "";
                            foreach ($row["otherApplicationAmendmentChargeInformation"] as $key => $charge) {
                                $anchors .=
                                    "<li>"
                                    . Html::a(
                                        "Revert fee to {$charge}",
                                        Url::to(
                                            [
                                                "revert-billing-charge",
                                                "fromBillingChargeId" => $row["applicationAmendmentChargeId"],
                                                "toBillingChargeId" => $key
                                            ]
                                        )
                                    )
                                    . "</li>";
                            }

                            $anchors .=
                                "<li>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-amendment-billing-charge",
                                            "billingChargeId" => $row["applicationAmendmentChargeId"]
                                        ]
                                    )
                                )
                                . "</li>";

                            return "<div class='dropdown'>"
                                . "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"
                                .   $row['applicationAmendmentChargeCost']
                                .   "<span class='caret'></span>"
                                . "</button>"
                                . "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>"
                                .   $anchors
                                . "</ul>"
                                . "</div>";
                        }
                    }
                ],





                // [
                //     "format" => "raw",
                //     "label" => "Application Amendment Fee",
                //     "value" => function ($row) {
                //         if ($row["applicationAmendmentChargeCost"] == true) {
                //             return Html::a(
                //                 $row["applicationAmendmentChargeCost"],
                //                 Url::to(
                //                     [
                //                         "edit-application-amendment-billing-charge",
                //                         "billingChargeId" => $row["applicationAmendmentChargeId"]
                //                     ]
                //                 ),
                //                 ["id" => "edit-application-amendment-billing-charge-button"]
                //             );
                //         } else {
                //             return Html::a(
                //                 "Add",
                //                 Url::to(
                //                     [
                //                         "add-application-amendment-billing-charge-to-application-period",
                //                         "applicationPeriodId" => $row["applicationPeriodId"]
                //                     ]
                //                 ),
                //                 ["id" => "add-application-amendment-billing-charge-button"]
                //             );
                //         }
                //     }
                // ],
            ]
        ]
    );
?>