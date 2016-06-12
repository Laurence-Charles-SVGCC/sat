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
    
     $this->title = 'Academic Performance Summary';
     $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
     $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview'])];
     $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'programmecatalogid' => $programmecatalogid,  'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/programmes/programmes/index']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/programme.png');?>" alt="award avatar">
                    <span class="custom_module_label" > Welcome to the Programme Management System</span> 
                    <img src ="<?=Url::to('../images/programme.png');?>" alt="award avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                <br/>

                <div style = 'margin-left: 2.5%;'>
                    <?php if($asc_dataprovider):?>
                        <div id="asc-broadsheet">
                            <h2 class="custom_h2" style="margin-left:2.5%"><?=$programme_name?> Performance Report</h2>
                            <div id="summary-export">
                                <?= ExportMenu::widget([
                                        'dataProvider' => $asc_dataprovider,
                                        'columns' => [
                                                [
                                                    'attribute' => 'code',
                                                    'format' => 'text',
                                                    'label' => 'Code'
                                                ],
                                                [
                                                    'attribute' => 'name',
                                                    'format' => 'text',
                                                    'label' => 'Name'
                                                ],
                                                [
                                                    'attribute' => 'semester',
                                                    'format' => 'text',
                                                    'label' => 'Semester'
                                                ],
                                                [
                                                    'attribute' => 'coursetype',
                                                    'format' => 'text',
                                                    'label' => 'Type'
                                                ],
                                                [
                                                    'attribute' => 'passcriteria',
                                                    'format' => 'text',
                                                    'label' => 'Pass Criteria'
                                                ],
                                                [
                                                    'attribute' => 'passfailtype',
                                                    'format' => 'text',
                                                    'label' => 'GPA Consideration'
                                                ],
                                                [
                                                    'attribute' => 'credits',
                                                    'format' => 'text',
                                                    'label' => 'Credits'
                                                ],
                                                [
                                                    'attribute' => 'Coursework Weight',
                                                    'format' => 'text',
                                                    'label' => 'coursework'
                                                ],
                                                [
                                                    'attribute' => 'Exam Weight',
                                                    'format' => 'text',
                                                    'label' => 'exam'
                                                ],
                                            ],
                                        'fontAwesome' => true,
                                        'dropdownOptions' => [
                                            'label' => 'Select Export Type',
                                            'class' => 'btn btn-default'
                                        ],
                                        'asDropdown' => false,
                                        'showColumnSelector' => false,
                                        'filename' => $filename,
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
                                        'dataProvider' => $asc_dataprovider,
                                        'options' => ['style' => 'width: 100%; margin: 0 auto;'],
                                        'columns' => [
                                            [
                                                'attribute' => 'code',
                                                'format' => 'text',
                                                'label' => 'Code'
                                            ],
                                            [
                                                'attribute' => 'name',
                                                'format' => 'text',
                                                'label' => 'Name'
                                            ],
                                            [
                                                'attribute' => 'semester',
                                                'format' => 'text',
                                                'label' => 'Semester'
                                            ],
                                            [
                                                'attribute' => 'credits',
                                                'format' => 'text',
                                                'label' => 'Credits'
                                            ],
                                            [
                                                'attribute' => 'coursework',
                                                'format' => 'text',
                                                'label' => 'Coursework Weight'
                                            ],
                                            [
                                                'attribute' => 'exam',
                                                'format' => 'text',
                                                'label' => 'Exam Weight'
                                            ],
                                        ],
                                    ]); 
                                ?>
                            </div>
                        </div>
                    <?php endif;?>


                    <?php if($cape_dataprovider):?>
                        <div id="accepted-listing">
                            <h2 class="custom_h2" style="margin-left:2.5%"><?=$programme_name?> Performance Report</h2>
                            <div id="accepted-export">
                                <?= ExportMenu::widget([
                                        'dataProvider' => $cape_dataprovider,
                                        'columns' => [
                                                [
                                                    'attribute' => 'code',
                                                    'format' => 'text',
                                                    'label' => 'Code'
                                                ],
                                                [
                                                    'attribute' => 'name',
                                                    'format' => 'text',
                                                    'label' => 'Name'
                                                ],
                                                [
                                                    'attribute' => 'subject',
                                                    'format' => 'text',
                                                    'label' => 'Subject'
                                                ],
                                                [
                                                    'attribute' => 'semester',
                                                    'format' => 'text',
                                                    'label' => 'Semester'
                                                ],
                                                [
                                                    'attribute' => 'Cousework Weight',
                                                    'format' => 'text',
                                                    'label' => 'coursework'
                                                ],
                                                [
                                                    'attribute' => 'Exam Weight',
                                                    'format' => 'text',
                                                    'label' => 'exam'
                                                ],
                                            ],
                                        'fontAwesome' => true,
                                        'dropdownOptions' => [
                                            'label' => 'Select Export Type',
                                            'class' => 'btn btn-default'
                                        ],
                                        'asDropdown' => false,
                                        'showColumnSelector' => false,
                                        'filename' => $filename,
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
                                        'dataProvider' => $cape_dataprovider,
                                        'options' => ['style' => 'width: 100%; margin: 0 auto;'],
                                        'columns' => [
                                            [
                                                'attribute' => 'code',
                                                'format' => 'text',
                                                'label' => 'Code'
                                            ],
                                            [
                                                'attribute' => 'name',
                                                'format' => 'text',
                                                'label' => 'Name'
                                            ],
                                            [
                                                'attribute' => 'subject',
                                                'format' => 'text',
                                                'label' => 'Subject'
                                            ],
                                            [
                                                'attribute' => 'semester',
                                                'format' => 'text',
                                                'label' => 'Semester'
                                            ],
                                            [
                                                'attribute' => 'Cousework Weight',
                                                'format' => 'text',
                                                'label' => 'coursework'
                                            ],
                                            [
                                                'attribute' => 'Exam Weight',
                                                'format' => 'text',
                                                'label' => 'exam'
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
