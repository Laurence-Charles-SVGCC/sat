<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>History</span>
            <?php if ($showVoidedReceiptDisplayButton === true) : ?>
                <button id="show-voided-receipts-button" type="button" class="pull-right btn btn-xs btn-warning" onclick="showVoidedReceiptsAndHideToggleButton()">
                    Show Voids
                </button>
            <?php endif; ?>
        </h3>
    </div>
    <div class="panel-body">
        <?=
            GridView::widget(
                [
                    "dataProvider" => $dataProvider,
                    "columns" => [
                        [
                            "label" => "Receipt#",
                            "format" => "raw",
                            "value" => function ($row) {
                                return Html::a(
                                    $row["receiptNumber"],
                                    Url::toRoute([
                                        "payments/view-receipt",
                                        "id" => $row["id"],
                                        "username" => $row["username"]
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
                                return date_format(new \DateTime($row["datePaid"]), "F j, Y");;
                            }
                        ],
                        [
                            "attribute" => "applicationPeriod",
                            "format" => "text",
                            "label" => "Period"
                        ],
                    ],
                ]
            );
        ?>
    </div>
</div>

<script>
    function showVoidedReceiptsAndHideToggleButton() {
        const showVoidReceiptsButton =
            document.getElementById("show-voided-receipts-button");

        const voidedReceiptListing =
            document.getElementById("voided-receipt-listing");

        if (showVoidReceiptsButton != null &&
            voidedReceiptListing != null &&
            voidedReceiptListing.style.display === "none") {
            voidedReceiptListing.style.display = "block";
            showVoidReceiptsButton.style.display = "none";
        }
    }
</script>