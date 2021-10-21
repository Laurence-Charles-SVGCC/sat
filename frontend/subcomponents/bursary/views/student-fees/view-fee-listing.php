<?php

use common\models\AcademicOfferingModel;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Student Fee Application Period Catalog";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>


<h1><?= $this->title ?></h1>

<?=
    GridView::widget(
        [
            "dataProvider" => $dataProvider,
            "columns" => [
                [
                    "attribute" => "periodName",
                    "format" => "text",
                    "label" => "Application Period"
                ],
                [
                    "format" => "raw",
                    "label" => "Programmes",
                    "value" => function ($row) {
                        if ($row["academicOfferings"] == false) {
                            return "";
                        } else {
                            $offerings = $row["academicOfferings"];
                            $offeringAsString = "";
                            foreach ($offerings as $offering) {
                                $name = AcademicOfferingModel::getProgrammeName(
                                    $offering
                                );
                                if ($name == false) {
                                    continue;
                                }
                                $url =
                                    Url::to(
                                        [
                                            'view-academic-offering-fee-listing',
                                            'applicationPeriodId' => $row['applicationPeriodId'],
                                            'academicOfferingId' => $offering->academicofferingid

                                        ]
                                    );
                                $offeringAsString .= "<a href={$url}>{$name}</a><br/>";
                            }
                            return $offeringAsString;
                        }
                    }
                ],
                [
                    "format" => "raw",
                    "label" => "Action",
                    "value" => function ($row) {
                        return Html::a(
                            "Update Catalog",
                            Url::to(
                                [
                                    "view-application-period-student-fee-dashboard",
                                    "applicationPeriodId" => $row["applicationPeriodId"]
                                ]
                            ),
                            [
                                "id" => "view-application-period-student-fee-dashboard-button",
                                "class" => "btn btn-primary"
                            ]
                        );
                    }
                ],
            ]
        ]
    );
?>