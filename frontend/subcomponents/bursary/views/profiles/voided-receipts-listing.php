<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="voided-receipt-listing" class="panel panel-default" style="display:none">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Voided Receipts</span>
            <button id="hide-voided-receipts-button" type="button" class="pull-right btn btn-xs btn-warning" onclick="hideVoidedReceipts()">
                Hide
            </button>
        </h3>
    </div>
    <div class="panel-body">
        <?=
            GridView::widget(
                [
                    "dataProvider" => $voidedReceiptsDataProvider,
                    "columns" => [
                        [
                            "label" => "Receipt#",
                            "format" => "raw",
                            "value" => function ($row) {
                                return Html::a(
                                    $row["receiptNumber"],
                                    Url::toRoute([
                                        "payments/view-voided-receipt",
                                        "id" => $row["id"]
                                    ]),
                                    [
                                        "title" => "View",
                                        "style" => "margin:0px 20px"
                                    ]
                                );
                            }
                        ],
                        [
                            "attribute" => "notes",
                            "format" => "text",
                            "label" => "Notes"
                        ],
                    ],
                ]
            );
        ?>
    </div>
</div>

<script>
    function hideVoidedReceipts() {
        const voidedReceiptListing =
            document.getElementById("voided-receipt-listing");

        const showVoidReceiptsButton =
            document.getElementById("show-voided-receipts-button");

        const visibility = voidedReceiptListing.style.display;

        if (showVoidReceiptsButton != null &&
            voidedReceiptListing != null &&
            visibility === "block") {
            voidedReceiptListing.style.display = "none";
            showVoidReceiptsButton.style.display = "block";
        }
    }
</script>