<?php

use kartik\grid\GridView;
use common\models\AcademicOfferingModel;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $title;

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Programme Catalog", "url" => ["student-fees/view-fee-listing"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?=
    Html::a(
        "Add Programme Fee",
        Url::toRoute([
            "add-fee-to-academic-offering",
            "applicationPeriodId" => $applicationPeriodId,
            "academicOfferingId" => $academicOfferingId
        ]),
        [
            "id" => "add-fee-to-academic-offering-button",
            "class" => "btn btn-success btn-md pull-right",
            // "style" => "margin-bottom: 200px"
        ]
    );
?>
<br /><br />
<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "attribute" => "billingType",
                    "format" => "text",
                    "label" => "Fee"
                ],
                [
                    "attribute" => "class",
                    "format" => "text",
                    "label" => "Type"
                ],
                [
                    "format" => "raw",
                    "label" => "Cost",
                    "value" => function ($row) {
                        if ($row["class"] == "Cohort") {
                            return $row['cost'];
                        } elseif (
                            $row["class"] == "Programme Specific"
                            && $row["pastCosts"] == false
                        ) {
                            $anchors = "";
                            $anchors .=
                                "<li>"
                                . Html::a(
                                    "Create new fee",
                                    Url::to(
                                        [
                                            "update-academic-offering-fee",
                                            "billingChargeId" => $row["billingChargeId"],
                                            "academicOfferingId" => $row["academicOfferingId"],
                                            "applicationPeriodId" => $row["applicationPeriodId"]

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
                        } elseif (
                            $row["class"] == "Programme Specific"
                            && $row["pastCosts"] == true
                        ) {
                            $anchors = "";
                            foreach ($row["pastCosts"] as $key => $charge) {
                                $anchors .=
                                    "<li>"
                                    . Html::a(
                                        "Revert fee to {$charge}",
                                        Url::to(
                                            [
                                                "revert-billing-charge",
                                                "fromBillingChargeId" => $row["billingChargeId"],
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
                                            "update-academic-offering-fee",
                                            "billingChargeId" => $row["billingChargeId"],
                                            "academicOfferingId" => $row["academicOfferingId"],
                                            "applicationPeriodId" => $row["applicationPeriodId"]
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
                    }
                ],
            ]
        ]
    );
?>