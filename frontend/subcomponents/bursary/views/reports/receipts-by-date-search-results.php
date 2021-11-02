<?php

use kartik\export\ExportMenu;
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

<div class="report-export">
    <?=
        ExportMenu::widget(
            [
                "dataProvider" => $dataProvider,
                "columns" => [
                    [
                        "format" => "raw",
                        "label" => "Receipt Number",
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
                        "format" => "text",
                        "label" => "Date Paid",
                        "value" => function ($row) {
                            return date_format(
                                new \DateTime($row["date"]),
                                "F j, Y"
                            );
                        }
                    ],
                    [
                        "format" => "text",
                        "label" => "Timestamp",
                        "value" => function ($row) {
                            return date_format(
                                new \DateTime($row["timestamp"]),
                                "F j, Y - g:i a"
                            );
                        }
                    ],
                    [
                        "attribute" => "customerUsername",
                        "format" => "text",
                        "label" => "Username"
                    ],
                    [
                        "attribute" => "customerFirstname",
                        "format" => "text",
                        "label" => "First Name"
                    ],
                    [
                        "attribute" => "customerLastname",
                        "format" => "text",
                        "label" => "Last Name"
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
                ],
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Select Export Type',
                    'class' => 'btn btn-default'
                ],
                'asDropdown' => false,
                'showColumnSelector' => false,
                'filename' => "Receipts Report",
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_EXCEL_X => false,
                    ExportMenu::FORMAT_CSV => false
                ],
            ]
        );
    ?>
</div><br />

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "format" => "raw",
                    "label" => "Receipt Number",
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
                    "format" => "text",
                    "label" => "Date Paid",
                    "value" => function ($row) {
                        return date_format(
                            new \DateTime($row["date"]),
                            "F j, Y"
                        );
                    }
                ],
                [
                    "format" => "text",
                    "label" => "Timestamp",
                    "value" => function ($row) {
                        return date_format(
                            new \DateTime($row["timestamp"]),
                            "F j, Y - g:i a"
                        );
                    }
                ],
                [
                    "attribute" => "customerUsername",
                    "format" => "text",
                    "label" => "Username"
                ],
                [
                    "attribute" => "customerFirstname",
                    "format" => "text",
                    "label" => "First Name"
                ],
                [
                    "attribute" => "customerLastname",
                    "format" => "text",
                    "label" => "Last Name"
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