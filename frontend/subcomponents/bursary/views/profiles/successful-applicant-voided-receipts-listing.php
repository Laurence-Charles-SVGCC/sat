<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="successful-applicant-voided-receipt-listing" class="panel panel-default" style="display:none">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span>Voided Receipts</span>
            <button id="successful-applicant-hide-voided-receipts-button" type="button" class="pull-right btn btn-xs btn-warning" onclick="hideSuccessfulApplicantsVoidedReceipts()">
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
    function hideSuccessfulApplicantsVoidedReceipts() {
        const voidedReceiptListing =
            document.getElementById("successful-applicant-voided-receipt-listing");

        const toggleButton =
            document.getElementById("successful-applicant-voided-receipts-visibility-toggle-button");

        const elementsExist =
            toggleButton != null &&
            voidedReceiptListing != null;

        if (elementsExist == true) {
            toggleButton.style.display = "block";
            voidedReceiptListing.style.display = "none";
        }
    }
</script>