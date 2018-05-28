<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;

    use frontend\models\Offer;
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    
     $this->title = 'Academic Offering Intake';
     $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
     $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
     $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'programmecatalogid' => $programmecatalogid,  'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<h2 class="text-center"><?=$programme_name?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div style="width:98%; margin: 0 auto;">
        <?php if($summary_dataProvider):?>
            <div id="summary-listing">
                <h2><?= $summary_header?></h2>
                <div id="summary-export">
                    <?= ExportMenu::widget([
                            'dataProvider' => $summary_dataProvider,
                            'columns' => [
                                    [
                                        'attribute' => 'name',
                                        'format' => 'text',
                                        'label' => 'Programme/Subject'
                                    ],
                                    [
                                        'attribute' => 'accepted_males',
                                        'format' => 'text',
                                        'label' => 'Accepted Males'
                                    ],
                                    [
                                        'attribute' => 'accepted_females',
                                        'format' => 'text',
                                        'label' => 'Accepted Females'
                                    ],
                                    [
                                        'attribute' => 'accepted',
                                        'format' => 'text',
                                        'label' => 'Number of Students Accepted'
                                    ],
                                    [
                                        'attribute' => 'enrolled_males',
                                        'format' => 'text',
                                        'label' => 'Enrolled Males'
                                    ],
                                    [
                                        'attribute' => 'enrolled_females',
                                        'format' => 'text',
                                        'label' => 'Enrolled Females'
                                    ],
                                    [
                                        'attribute' => 'enrolled',
                                        'format' => 'text',
                                        'label' => 'Number of Students Enrolled'
                                    ],
                                ],
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Select Export Type',
                                'class' => 'btn btn-default'
                            ],
                            'asDropdown' => false,
                            'showColumnSelector' => false,
                            'filename' => $accepted_filename,
                            'exportConfig' => [
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_EXCEL => false,
                                ExportMenu::FORMAT_EXCEL_X => false
                            ],
                        ]);
                    ?>
                </div>

                <div id="summary-details">
                    <?= GridView::widget([
                            'dataProvider' => $summary_dataProvider,
                            'options' => [],
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'format' => 'text',
                                    'label' => 'Programme/Subject'
                                ],
                                [
                                    'attribute' => 'accepted_males',
                                    'format' => 'text',
                                    'label' => 'Accepted Males'
                                ],
                                [
                                    'attribute' => 'accepted_females',
                                    'format' => 'text',
                                    'label' => 'Accepted Females'
                                ],
                                [
                                    'attribute' => 'accepted',
                                    'format' => 'text',
                                    'label' => 'Number of Students Accepted'
                                ],
                                [
                                    'attribute' => 'enrolled_males',
                                    'format' => 'text',
                                    'label' => 'Enrolled Males'
                                ],
                                [
                                    'attribute' => 'enrolled_females',
                                    'format' => 'text',
                                    'label' => 'Enrolled Females'
                                ],
                                [
                                    'attribute' => 'enrolled',
                                    'format' => 'text',
                                    'label' => 'Number of Students Enrolled'
                                ],
                            ],
                        ]); 
                    ?>
                </div>
            </div>
        <?php endif;?>


        <?php if($accepted_dataProvider):?>
            <div id="accepted-listing">
                <h2><?= $accepted_header?></h2>
                <div id="accepted-export">
                    <?= ExportMenu::widget([
                            'dataProvider' => $accepted_dataProvider,
                            'columns' => [
                                    [
                                        'attribute' => 'username',
                                        'format' => 'text',
                                        'label' => 'Applicant ID'
                                    ],
                                    [
                                        'attribute' => 'title',
                                        'format' => 'text',
                                        'label' => 'Title'
                                    ],
                                    [
                                        'attribute' => 'firstname',
                                        'format' => 'text',
                                        'label' => 'First Name'
                                    ],
                                    [
                                        'attribute' => 'middlename',
                                        'format' => 'text',
                                        'label' => 'Middle Name'
                                    ],
                                    [
                                        'attribute' => 'lastname',
                                        'format' => 'text',
                                        'label' => 'Last Name'
                                    ],
                                    [
                                        'attribute' => 'programme',
                                        'format' => 'text',
                                        'label' => 'Programme'
                                    ],
                                ],
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Select Export Type',
                                'class' => 'btn btn-default'
                            ],
                            'asDropdown' => false,
                            'showColumnSelector' => false,
                            'filename' => $accepted_filename,
                            'exportConfig' => [
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_EXCEL => false,
                                ExportMenu::FORMAT_EXCEL_X => false
                            ],
                        ]);
                    ?>
                </div>

                <div id="accepted-details">
                    <?= GridView::widget([
                            'dataProvider' => $accepted_dataProvider,
                            'options' => ['style' => 'width: 100%; margin: 0 auto;'],
                            'columns' => [
                                [
                                    'attribute' => 'username',
                                    'format' => 'text',
                                    'label' => 'Username'
                                ],
                                [
                                    'attribute' => 'firstname',
                                    'format' => 'text',
                                    'label' => 'First Name'
                                ],
                                [
                                    'attribute' => 'lastname',
                                    'format' => 'text',
                                    'label' => 'Last Name'
                                ],
                                [
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Programme'
                                ],
                            ],
                        ]); 
                    ?>
                </div>
            </div>
        <?php endif;?>



        <?php if($enrolled_dataProvider):?>
            <div id="accepted-listing">
                <h2><?= $enrolled_header?></h2>
                <div id="accepted-export">
                    <?= ExportMenu::widget([
                            'dataProvider' => $enrolled_dataProvider,
                            'columns' => [
                                    [
                                        'attribute' => 'username',
                                        'format' => 'text',
                                        'label' => 'Applicant ID'
                                    ],
                                    [
                                        'attribute' => 'title',
                                        'format' => 'text',
                                        'label' => 'Title'
                                    ],
                                    [
                                        'attribute' => 'firstname',
                                        'format' => 'text',
                                        'label' => 'First Name'
                                    ],
                                    [
                                        'attribute' => 'middlename',
                                        'format' => 'text',
                                        'label' => 'Middle Name'
                                    ],
                                    [
                                        'attribute' => 'lastname',
                                        'format' => 'text',
                                        'label' => 'Last Name'
                                    ],
                                    [
                                        'attribute' => 'programme',
                                        'format' => 'text',
                                        'label' => 'Programme'
                                    ],
                                ],
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Select Export Type',
                                'class' => 'btn btn-default'
                            ],
                            'asDropdown' => false,
                            'showColumnSelector' => false,
                            'filename' => $enrolled_filename,
                            'exportConfig' => [
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_EXCEL => false,
                                ExportMenu::FORMAT_EXCEL_X => false
                            ],
                        ]);
                    ?>
                </div>

                <div id="enrolled-details">
                    <?= GridView::widget([
                            'dataProvider' => $enrolled_dataProvider,
                            'options' => [],
                            'columns' => [
                                [
                                    'attribute' => 'username',
                                    'format' => 'text',
                                    'label' => 'Username'
                                ],
                                [
                                    'attribute' => 'firstname',
                                    'format' => 'text',
                                    'label' => 'First Name'
                                ],
                                [
                                    'attribute' => 'lastname',
                                    'format' => 'text',
                                    'label' => 'Last Name'
                                ],
                                [
                                    'attribute' => 'programme',
                                    'format' => 'text',
                                    'label' => 'Programme'
                                ],
                            ],
                        ]); 
                    ?>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>