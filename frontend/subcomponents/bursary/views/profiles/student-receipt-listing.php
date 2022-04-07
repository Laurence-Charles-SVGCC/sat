<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div id="student-receipt-listing">
    <?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "label" => "Receipt Number",
                    "format" => "raw",
                    "value" => function ($row) {
                        return Html::a(
                            $row["receiptNumber"],
                            Url::toRoute([
                                "payments/view-receipt",
                                "id" => $row["id"],
                                "username" => $row["username"],
                                "from" => "student-profile"
                            ]),
                            [
                                "title" => "View",
                                "style" => "margin:0px  20px"
                            ]
                        );
                    }
                ],
                [
                    "attribute" => "billingDetails",
                    "format" => "text",
                    "label" => "Billings"
                ],
                [
                    "attribute" => "total",
                    "format" => "text",
                    "label" => "Total"
                ],
                [
                    "format" => "raw",
                    "label" => "Date Paid",
                    "value" => function ($row) {
                        return date_format(
                            new \DateTime($row["datePaid"]),
                            "F j, Y"
                        );
                    }
                ],
                [
                    "attribute" => "applicationPeriod",
                    "format" => "text",
                    "label" => "Registration Period"
                ],
            ],
        ]
    );
    ?>
</div>