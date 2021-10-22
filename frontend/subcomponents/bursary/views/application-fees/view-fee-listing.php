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
                                "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-submission-billing-charge",
                                            "billingChargeId" => $row["applicationSubmissionChargeId"]
                                        ]
                                    ),
                                    [
                                        "class" => "btn btn-primary",
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>";

                            return "<ul>"
                                . "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "<strong>$ {$row['applicationSubmissionChargeCost']}</strong>",
                                    null,
                                    [
                                        "class" => "btn btn-default",
                                        "disabled" => true,
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>"
                                . $anchors
                                . "</ul>";
                        } elseif (
                            $row["applicationSubmissionChargeCost"] == true
                            && $row["otherApplicationSubmissionChargeInformation"] == true
                        ) {
                            $anchors = "";
                            foreach ($row["otherApplicationSubmissionChargeInformation"] as $key => $charge) {
                                $anchors .=
                                    "<li style ='list-style-type:none; margin-top:5px'>"
                                    . Html::a(
                                        "Revert fee to {$charge}",
                                        Url::to(
                                            [
                                                "revert-billing-charge",
                                                "fromBillingChargeId" => $row["applicationSubmissionChargeId"],
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

                            $anchors .=
                                "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-submission-billing-charge",
                                            "billingChargeId" => $row["applicationSubmissionChargeId"]
                                        ]
                                    ),
                                    [
                                        "class" => "btn btn-primary",
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>";

                            return "<ul>"
                                . "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "<strong>$ {$row['applicationSubmissionChargeCost']}</strong>",
                                    null,
                                    [
                                        "class" => "btn btn-default",
                                        "disabled" => true,
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>"
                                . $anchors
                                . "</ul>";
                        }
                    }
                ],
                //////////
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
                                "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-amendment-billing-charge",
                                            "billingChargeId" => $row["applicationAmendmentChargeId"]
                                        ]
                                    ),
                                    [
                                        "class" => "btn btn-primary",
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>";

                            return "<ul>"
                                . "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "<strong>$ {$row['applicationAmendmentChargeCost']}</strong>",
                                    null,
                                    [
                                        "class" => "btn btn-default",
                                        "disabled" => true,
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>"
                                . $anchors
                                . "</ul>";
                        } elseif (
                            $row["applicationAmendmentChargeCost"] == true
                            && $row["otherApplicationAmendmentChargeInformation"] == true
                        ) {
                            $anchors = "";
                            foreach ($row["otherApplicationAmendmentChargeInformation"] as $key => $charge) {
                                $anchors .=
                                    "<li style ='list-style-type:none; margin-top:5px'>"
                                    . Html::a(
                                        "Revert fee to {$charge}",
                                        Url::to(
                                            [
                                                "revert-billing-charge",
                                                "fromBillingChargeId" => $row["applicationAmendmentChargeId"],
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

                            $anchors .=
                                "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "edit-application-amendment-billing-charge",
                                            "billingChargeId" => $row["applicationAmendmentChargeId"]
                                        ]
                                    ),
                                    [
                                        "class" => "btn btn-primary",
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>";

                            return "<ul>"
                                . "<li style ='list-style-type:none; margin-top:5px'>"
                                . Html::a(
                                    "<strong>$ {$row['applicationAmendmentChargeCost']}</strong>",
                                    null,
                                    [
                                        "class" => "btn btn-default",
                                        "disabled" => true,
                                        "style" => "border:0px; width:150px"
                                    ]
                                )
                                . "</li>"
                                . $anchors
                                . "</ul>";
                        }
                    }
                ],
            ]
        ]
    );
?>