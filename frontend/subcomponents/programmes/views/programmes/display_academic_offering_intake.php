<?php

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


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']);?>" title="Manage Programmes">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/programme.png" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                    <img src ="css/dist/img/header_images/programme.png" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$programme_name?></h1>
                <br/>

                <div style = 'margin-left: 2.5%;'>
                    <?php if($summary_dataProvider):?>
                        <div id="summary-listing">
                            <h2 class="custom_h2" style="margin-left:2.5%"><?= $summary_header?></h2>
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
                                        'options' => ['style' => 'width: 100%; margin: 0 auto;'],
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
                            <h2 class="custom_h2" style="margin-left:2.5%"><?= $accepted_header?></h2>
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
                            <h2 class="custom_h2" style="margin-left:2.5%"><?= $enrolled_header?></h2>
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
                </div>
            </div>
        </div>
    </div>