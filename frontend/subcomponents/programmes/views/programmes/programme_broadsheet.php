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
                
                <div style = 'margin-left: 0.5%;'>
                    <?php if($asc_dataprovider):?>
                        <div id="asc-broadsheet">
                            <h2 class="custom_h2" style="margin: 0 auto">Programme: <?=$programme_name?> (<?=$academic_year;?>)</h2>
                            
                            <div id ="asc-grade-entry-stats">
                                <h3><strong>Grade Entry Stats</strong></h3>
                                <ul>
                                    <li><strong>Total Courses</strong> - <?=$total_courses;?></li>
                                    <li><strong>Total Entered</strong> - <?=$total_entered;?></li>
                                    <li><strong>Total Outstanding</strong> - <span style="color: red"><?=$total_outstanding;?></span></li>
                                </ul>
                            </div>
                            
                            <div id="summary-export">
                                 <h3><strong>Click one of following links to download a detailed copy of the following report.</strong></h3>
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
                                                    'label' => 'Sem.'
                                                ],
                                                [
                                                    'attribute' => 'lecturer',
                                                    'format' => 'text',
                                                    'label' => 'Lecturer'
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
                                                    'attribute' => 'coursework',
                                                    'format' => 'text',
                                                    'label' => 'Coursework'
                                                ],
                                                [
                                                    'attribute' => 'exam',
                                                    'format' => 'text',
                                                    'label' => 'Exam'
                                                ],
                                                [
                                                    'attribute' => 'passes',
                                                    'format' => 'text',
                                                    'label' => 'Passes'
                                                ],
                                                [
                                                    'attribute' => 'fails',
                                                    'format' => 'text',
                                                    'label' => 'Fails'
                                                ],
                                                [
                                                    'attribute' => 'total',
                                                    'format' => 'text',
                                                    'label' => 'Enrolled'
                                                ],
                                                [
                                                    'attribute' => 'pass_percent',
                                                    'format' => 'text',
                                                    'label' => 'Pass Rate'
                                                ],
                                                [
                                                    'attribute' => 'a_plus',
                                                    'format' => 'text',
                                                    'label' => 'A+'
                                                ],
                                                [
                                                    'attribute' => 'a',
                                                    'format' => 'text',
                                                    'label' => 'A'
                                                ],
                                                [
                                                    'attribute' => 'a_minus',
                                                    'format' => 'text',
                                                    'label' => 'A-'
                                                ],
                                                [
                                                    'attribute' => 'b_plus',
                                                    'format' => 'text',
                                                    'label' => 'B+'
                                                ],
                                                [
                                                    'attribute' => 'b',
                                                    'format' => 'text',
                                                    'label' => 'B'
                                                ],
                                                [
                                                    'attribute' => 'b_minus',
                                                    'format' => 'text',
                                                    'label' => 'B-'
                                                ],
                                                [
                                                    'attribute' => 'c_plus',
                                                    'format' => 'text',
                                                    'label' => 'C+'
                                                ],
                                                [
                                                    'attribute' => 'c',
                                                    'format' => 'text',
                                                    'label' => 'C'
                                                ],
                                                [
                                                    'attribute' => 'c_minus',
                                                    'format' => 'text',
                                                    'label' => 'C-'
                                                ],
                                                [
                                                    'attribute' => 'd_plus',
                                                    'format' => 'text',
                                                    'label' => 'D+'
                                                ],
                                                [
                                                    'attribute' => 'd',
                                                    'format' => 'text',
                                                    'label' => 'D'
                                                ],
                                                [
                                                    'attribute' => 'd_minus',
                                                    'format' => 'text',
                                                    'label' => 'D-'
                                                ],
                                                [
                                                    'attribute' => 'mode',
                                                    'format' => 'text',
                                                    'label' => 'Mode'
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
                                                'attribute' => 'lecturer',
                                                'format' => 'text',
                                                'label' => 'Lecturer'
                                            ],
                                            [
                                                'attribute' => 'credits',
                                                'format' => 'text',
                                                'label' => 'Credits'
                                            ],
                                            [
                                                'attribute' => 'coursework',
                                                'format' => 'text',
                                                'label' => 'CW'
                                            ],
                                            [
                                                'attribute' => 'exam',
                                                'format' => 'text',
                                                'label' => 'Exam'
                                            ],
                                            [ 
                                                'attribute' => 'passes',
                                                'format' => 'text',
                                                'label' => 'Passes'
                                            ],
                                            [
                                                'attribute' => 'fails',
                                                'format' => 'text',
                                                'label' => 'Fails'
                                            ],
                                            [
                                                'attribute' => 'total',
                                                'format' => 'text',
                                                'label' => 'Enrolled'
                                            ],
                                            [
                                                'attribute' => 'pass_percent',
                                                'format' => 'text',
                                                'label' => 'Pass Rate'
                                            ],
                                            [
                                                'attribute' => 'mode',
                                                'format' => 'text',
                                                'label' => 'Mode'
                                            ],
                                        ],
                                    ]); 
                                ?>
                            </div>
                        </div>
                    <?php endif;?>


                    <?php if($cape_dataprovider):?>
                        <div id="accepted-listing">
                            <h2 class="custom_h2" style="margin-left:2.5%">Programme: <?=$programme_name?>  (<?=$academic_year;?>)</h2>
                            
                            <div id ="cape-grade-entry-stats">
                                <h3><strong>Grade Entry Stats</strong></h3>
                                <ul>
                                    <li><strong>Total Courses</strong> - <?=$total_courses;?></li>
                                    <li><strong>Total Entered</strong> - <?=$total_entered;?></li>
                                    <li><strong>Total Outstanding</strong> - <span style="color: red"><?=$total_outstanding;?></span></li>
                                </ul>
                            </div>
                            
                            <div id="accepted-export">
                                <h3><strong>Click one of following links to download a detailed copy of the following report.</strong></h3>
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
                                                    'attribute' => 'lecturer',
                                                    'format' => 'text',
                                                    'label' => 'Lecturer'
                                                ],
                                                [
                                                    'attribute' => 'coursework',
                                                    'format' => 'text',
                                                    'label' => 'Cousework'
                                                ],
                                                [
                                                    'attribute' => 'exam',
                                                    'format' => 'text',
                                                    'label' => 'Exam'
                                                ],
                                                [
                                                    'attribute' => 'passes',
                                                    'format' => 'text',
                                                    'label' => 'Passes'
                                                ],
                                                [
                                                    'attribute' => 'fails',
                                                    'format' => 'text',
                                                    'label' => 'Fails'
                                                ],
                                                [
                                                    'attribute' => 'total',
                                                    'format' => 'text',
                                                    'label' => 'Enrolled'
                                                ],
                                                [
                                                    'attribute' => 'pass_percent',
                                                    'format' => 'text',
                                                    'label' => 'Pass Rate'
                                                ],
                                                [
                                                    'attribute' => 'ninety_plus',
                                                    'format' => 'text',
                                                    'label' => '>=90'
                                                ],
                                                [
                                                    'attribute' => 'eighty_to_ninety',
                                                    'format' => 'text',
                                                    'label' => '80-90'
                                                ],
                                                [
                                                    'attribute' => 'seventy_to_eighty',
                                                    'format' => 'text',
                                                    'label' => '70-80'
                                                ],
                                                [
                                                    'attribute' => 'sixty_to_seventy',
                                                    'format' => 'text',
                                                    'label' => '60-70'
                                                ],
                                                [
                                                    'attribute' => 'fifty_to_sixty',
                                                    'format' => 'text',
                                                    'label' => '50-60'
                                                ],
                                                [
                                                    'attribute' => 'forty_to_fifty',
                                                    'format' => 'text',
                                                    'label' => '40-50'
                                                ],
                                                [
                                                    'attribute' => 'thirtyfive_to_forty',
                                                    'format' => 'text',
                                                    'label' => '35-40'
                                                ],
                                                [
                                                    'attribute' => 'minus_thirtyfive',
                                                    'format' => 'text',
                                                    'label' => '<35'
                                                ],
                                                [
                                                    'attribute' => 'mode',
                                                    'format' => 'text',
                                                    'label' => 'Mode'
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
                                                'label' => 'Sem.'
                                            ],
                                            [
                                                'attribute' => 'lecturer',
                                                'format' => 'text',
                                                'label' => 'Lecturer'
                                            ],
                                            [
                                                'attribute' => 'coursework',
                                                'format' => 'text',
                                                'label' => 'CW'
                                            ],
                                            [
                                                'attribute' => 'exam',
                                                'format' => 'text',
                                                'label' => 'Exam'
                                            ],
                                            [
                                                'attribute' => 'passes',
                                                'format' => 'text',
                                                'label' => 'Passes'
                                            ],
                                            [
                                                'attribute' => 'fails',
                                                'format' => 'text',
                                                'label' => 'Fails'
                                            ],
                                            [
                                                'attribute' => 'total',
                                                'format' => 'text',
                                                'label' => 'Enrolled'
                                            ],
                                            [
                                                'attribute' => 'pass_percent',
                                                'format' => 'text',
                                                'label' => 'Pass Rate'
                                            ],
                                            [
                                                'attribute' => 'mode',
                                                'format' => 'text',
                                                'label' => 'Mode'
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
