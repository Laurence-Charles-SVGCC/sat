<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
?>

<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Report Results';

$this->params["breadcrumbs"][] =
    ["label" => "Report Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Quality Assurance", "url" => ["quality-assurance/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Course Pass Rates", "url" => ["course-pass-rate/index"]];

$this->params["breadcrumbs"][] = $this->title;

?>

<h1><?= $this->title ?></h1>


<?php if (!empty($dataProvider)) : ?>
    <div class="report-export">
        <?=
        ExportMenu::widget(
            [
                "dataProvider" => $dataProvider,
                "columns" => [
                    [
                        "attribute" => "division",
                        "format" => "text",
                        "label" => "Division"
                    ],
                    [
                        "attribute" => "academicYear",
                        "format" => "text",
                        "label" => "Academic Year"
                    ],
                    [
                        "attribute" => "semester",
                        "format" => "text",
                        "label" => "Semester"
                    ],
                    [
                        "attribute" => "programme",
                        "format" => "text",
                        "label" => "Programme"
                    ],
                    [
                        "attribute" => "subject",
                        "format" => "text",
                        "label" => "Subject"
                    ],
                    [
                        "attribute" => "courseCode",
                        "format" => "text",
                        "label" => "Code"
                    ],
                    [
                        "attribute" => "courseName",
                        "format" => "text",
                        "label" => "Course"
                    ],
                    [
                        "attribute" => "totalStudents",
                        "format" => "text",
                        "label" => "Total Students"
                    ],
                    [
                        "attribute" => "noOfPasses",
                        "format" => "text",
                        "label" => "Passes"
                    ],
                    [
                        "attribute" => "noOfFails",
                        "format" => "text",
                        "label" => "Fails"
                    ],
                    [
                        "attribute" => "passRate",
                        "format" => "text",
                        "label" => "Pass Rate"
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
                "attribute" => "division",
                "format" => "text",
                "label" => "Division"
            ],
            [
                "attribute" => "academicYear",
                "format" => "text",
                "label" => "Academic Year"
            ],
            [
                "attribute" => "semester",
                "format" => "text",
                "label" => "Semester"
            ],
            [
                "attribute" => "programme",
                "format" => "text",
                "label" => "Programme"
            ],
            [
                "attribute" => "subject",
                "format" => "text",
                "label" => "Subject"
            ],
            [
                "attribute" => "courseCode",
                "format" => "text",
                "label" => "Code"
            ],
            [
                "attribute" => "courseName",
                "format" => "text",
                "label" => "Course"
            ],
            [
                "attribute" => "totalStudents",
                "format" => "text",
                "label" => "Total Students"
            ],
            [
                "attribute" => "noOfPasses",
                "format" => "text",
                "label" => "Passes"
            ],
            [
                "attribute" => "noOfFails",
                "format" => "text",
                "label" => "Fails"
            ],
            [
                "attribute" => "passRate",
                "format" => "text",
                "label" => "Pass Rate"
            ]
        ]
    ]
);
?>