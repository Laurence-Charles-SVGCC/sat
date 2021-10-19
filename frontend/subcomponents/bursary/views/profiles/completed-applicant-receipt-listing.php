<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">History</h3>
    </div>
    <div class="panel-body">
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