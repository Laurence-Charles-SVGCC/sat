<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Billing Types";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<?=
    Html::a(
        "Add Multiple Records",
        Url::toRoute(["billing-types/create-multiple"]),
        [
            "id" => "create-multiple-billing-types-button",
            "class" => "btn btn-success btn-md pull-right",
            "style" => "margin: 10px"
        ]
    );
?>

<?=
    Html::a(
        "Add Record",
        Url::toRoute(["billing-types/create"]),
        [
            "id" => "create-new-billing-type-button",
            "class" => "btn btn-success btn-md pull-right",
            "style" => "margin: 10px"
        ]
    );
?>
<br /><br /><br />

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    'attribute' => "name",
                    'format' => 'text'
                ],
                [
                    "attribute" => "billingCategory",
                    "format" => "text",
                    "label" => "Category"
                ],
                [
                    "attribute" => "division",
                    "format" => "text",
                    "label" => "Division"
                ],
                [
                    "class" => "yii\grid\ActionColumn",
                    "template" => "{view} {update} {delete}",
                    "buttons" => [
                        "view" => function ($url, $model) {
                            return Html::a(
                                " ",
                                Url::toRoute([
                                    "view",
                                    "id" => $model["id"]
                                ]),
                                [
                                    "title" => "View",
                                    "class" => "glyphicon glyphicon-eye-open",
                                    "style" => "margin:0px  20px"
                                ]
                            );
                        },
                        "update" => function ($url, $model) {
                            return Html::a(
                                " ",
                                Url::toRoute([
                                    "update",
                                    "id" => $model["id"]
                                ]),
                                [
                                    "title" => "Update",
                                    "class" => "glyphicon glyphicon-pencil",
                                    "style" => "margin:0px  20px"
                                ]
                            );
                        },
                        "delete" => function ($url, $model) {
                            if ($model["canDeleteBillingType"] == true) {
                                return Html::a(
                                    " ",
                                    Url::toRoute([
                                        "delete",
                                        "id" => $model["id"]
                                    ]),
                                    [
                                        "title" => "Delete",
                                        "class" => "glyphicon glyphicon-trash",
                                        "style" => "margin:0px  20px",
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Are you sure? This will delete item.',
                                        ]
                                    ]
                                );
                            } else {
                                return "";
                            }
                        }
                    ],
                ],
            ],
        ]
    );
?>