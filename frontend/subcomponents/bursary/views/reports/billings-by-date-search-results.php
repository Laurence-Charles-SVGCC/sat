<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Billings';

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Reports", "url" => ["reports/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Report", "url" => ["reports/billings-by-date"]];

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
                            $row["receiptId"],
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
                        return date_format(new \DateTime($row["date"]), "F j, Y");;
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
                    "attribute" => "billingType",
                    "format" => "text",
                    "label" => "Billing Type"
                ],
                [
                    "attribute" => "applicationPeriod",
                    "format" => "text",
                    "label" => "Application Period"
                ],
                [
                    "attribute" => "paymentMethod",
                    "format" => "text",
                    "label" => "Payment Method"
                ],
                [
                    "attribute" => "amountDue",
                    "format" => "text",
                    "label" => "Due"
                ],
                [
                    "attribute" => "amountPaid",
                    "format" => "text",
                    "label" => "Paid"
                ],
            ]
        ]
    );
?>