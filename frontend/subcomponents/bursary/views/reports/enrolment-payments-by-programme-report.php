<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<hr />
<?php if (!empty($dataProvider)) : ?>
    <div class="report-export">
        <?=
            ExportMenu::widget(
                [
                    "dataProvider" => $dataProvider,
                    "columns" => [
                        [
                            "attribute" => "username",
                            "format" => "text",
                            "label" => "First Name"
                        ],
                        [
                            "attribute" => "firstname",
                            "format" => "text",
                            "label" => "First Name"
                        ],
                        [
                            "attribute" => "lastname",
                            "format" => "text",
                            "label" => "Last Name"
                        ],
                        [
                            "attribute" => "email",
                            "format" => "text",
                            "label" => "Email"
                        ],
                        [
                            "attribute" => "enrolmentBillingChargesTotal",
                            "format" => "text",
                            "label" => "Enrollment Total"
                        ],
                        [
                            "attribute" => "enrolmentBillingChargesPaid",
                            "format" => "text",
                            "label" => "Fees Paid"
                        ],
                        [
                            "attribute" => "outstandingEnrolmentBalance",
                            "format" => "text",
                            "label" => "Balance"
                        ],
                        [
                            "attribute" => "outstandingBillingCharges",
                            "format" => "text",
                            "label" => "Charges Outstanding"
                        ],
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
                        // ExportMenu::FORMAT_CSV => false
                    ],
                ]
            );
        ?>
    </div><br />
<?php endif; ?>

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "attribute" => "username",
                    "format" => "text",
                    "label" => "First Name"
                ],
                [
                    "attribute" => "firstname",
                    "format" => "text",
                    "label" => "First Name"
                ],
                [
                    "attribute" => "lastname",
                    "format" => "text",
                    "label" => "Last Name"
                ],
                [
                    "attribute" => "enrolmentBillingChargesTotal",
                    "format" => "text",
                    "label" => "Enrollment Total"
                ],
                [
                    "attribute" => "enrolmentBillingChargesPaid",
                    "format" => "text",
                    "label" => "Fees Paid"
                ],
                [
                    "attribute" => "email",
                    "format" => "text",
                    "label" => "Email"
                ],
                [
                    "attribute" => "outstandingEnrolmentBalance",
                    "format" => "text",
                    "label" => "Balance"
                ],
                [
                    "attribute" => "outstandingBillingCharges",
                    "format" => "text",
                    "label" => "Charges Outstanding"
                ],
            ]
        ]
    );
?>