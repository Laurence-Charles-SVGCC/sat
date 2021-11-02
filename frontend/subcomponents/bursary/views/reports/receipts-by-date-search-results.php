<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Receipts';

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Reports", "url" => ["reports/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Report", "url" => ["reports/receipts-by-date"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "format" => "raw",
                    "label" => "Receipt ID",
                    "value" => function ($row) {
                        return Html::a(
                            $row["receiptNumber"],
                            Url::to(
                                [
                                    "payments/view-receipt",
                                    "id" => $row["receiptId"],
                                    "username" => $row["customerUsername"],
                                    "from" => $row["customerStatus"]
                                ]
                            ),
                            ["id" => "view-receipt"]
                        );
                    }
                ],
                [
                    "format" => "raw",
                    "label" => "Date Paid",
                    "value" => function ($row) {
                        return date_format(
                            new \DateTime($row["date"]),
                            "F j, Y"
                        );
                    }
                ],
                [
                    "attribute" => "customerUsername",
                    "format" => "text",
                    "label" => "Username"
                ],
                [
                    "attribute" => "customerFullName",
                    "format" => "text",
                    "label" => "Full Name"
                ],
                [
                    "attribute" => "paymentMethod",
                    "format" => "text",
                    "label" => "Payment Method"
                ],
                [
                    "attribute" => "amountPaid",
                    "format" => "text",
                    "label" => "Paid"
                ],
                [
                    "attribute" => "paymentProcessor",
                    "format" => "text",
                    "label" => "Collected By"
                ]
            ]
        ]
    );
?>