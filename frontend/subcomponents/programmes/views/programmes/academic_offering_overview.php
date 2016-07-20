<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\grid\GridView;
    use kartik\export\ExportMenu;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    
    $this->title = 'Academic Offering Overview';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
    
    $menu_items = [
        1 => "Manage Programme Booklets",
        2 => "View Course Details",
        3 => "View Intake Reports",
        4 => "View Performance Report",
    ];
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
                
                    <div id="summary">
                        <?php if(!$programme_info):?>
                            <h3>Unable to retrieve programme summary</h3>
                        <?php else:?>
                            <table class='table table-hover' style='width: 90%; margin: 0 auto;'>
                                <tr>
                                    <th class="custom_h2" colspan="6" style="text-align:center; color:green">Academic Offering Summary</th>
                                </tr>

                                <tr>
                                    <th>Qualification</th>
                                    <td><?=$programme_info['qualificationtype'];?></td>
                                    <th>Examination Body</th>
                                    <td><?=$programme_info['exambody'];?></td>
                                    <th>Programme Type</th>
                                    <td><?=$programme_info['programmetype'];?></td>
                                </tr>

                                 <tr>
                                    <th>Specialisation</th>
                                    <?php if($programme_info['specialisation']):?>
                                        <td><?=$programme_info['specialisation'];?></td>
                                    <?php else:?>
                                        <td><?="N/A";?></td>
                                    <?php endif;?>
                                    <th>Duration</th>
                                    <td><?= $programme_info['duration'];?></td>
                                    <th>Creation Date</th>
                                    <td><?= $programme_info['creationdate'];?></td>
                                </tr>

                                 <tr>
                                    <th>Department</th>
                                    <td><?=$programme_info['department'];?></td>
                                    <th>Cohort</th>
                                    <td><?=$cohort?>
                                    <th>Most Recent Coordinator(s)</th>
                                    <?php if($cordinator_details):?>
                                        <td><?=$cordinator_details?></td>
                                    <?php else:?>
                                        <td>No appointees</td>
                                    <?php endif;?>
                                 </tr>
                            </table></br>
                         <?php endif;?>
                     </div><br/><br/>
                
                    <div id="offering-options-panel" style='width: 90%; margin: 0 auto;'>
                        <div id="options">
                             Please select one of the following actions:
                             <?= Html::radioList('academic_offering_options', null, $menu_items, [ 'onclick'=> 'toggleAcademicOfferingOptions();',  'style' => 'width: 30%']);?>
                        </div><br/>

                        <div id="manage-booklets" style="display:none;">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Manage Programme Booklets</legend>
                                <?php if(ProgrammeCatalog::getBooklets($programme_info['divisionid'], $programme_info['programmecatalogid'], $academicofferingid) == true):?>
                                    <a class="btn btn-info glyphicon glyphicon-download-alt" style="width:20%; margin-left:5%; margin-right:15%"
                                            href=<?=Url::toRoute(['/subcomponents/programmes/programmes/download-booklet', 
                                                                                'divisionid' => $programme_info['divisionid'],
                                                                                'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                'academicofferingid' => $academicofferingid]);
                                                    ?> role="button"> Download Booklet
                                    </a>
                                    <a class="btn btn-warning glyphicon glyphicon-refresh" style="width:20%; margin-right:15%;" 
                                            href=<?=Url::toRoute(['/subcomponents/programmes/programmes/replace-booklet',
                                                                                    'divisionid' => $programme_info['divisionid'],
                                                                                    'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                    'academicofferingid' => $academicofferingid,]);
                                                        ?> role="button"> Replace Booklet
                                         </a>
                                    <?= Html::a(' Delete Booklet', 
                                                    ['delete-booklet',  'divisionid' => $programme_info['divisionid'],
                                                                     'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                     'academicofferingid' => $academicofferingid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete the current booklet?',
                                                            'method' => 'post',
                                                        ],
                                                        'style' => 'width:20%',
                                                    ]);
                                    ?>
                                    <?php else:?>
                                         <a class="btn btn-info glyphicon glyphicon-plus" style="width:20%; margin:0 auto;" 
                                            href=<?=Url::toRoute(['/subcomponents/programmes/programmes/upload-booklet',
                                                                                    'divisionid' => $programme_info['divisionid'],
                                                                                    'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                    'academicofferingid' => $academicofferingid]);
                                                        ?> role="button"> Upload Booklet
                                         </a>
                                    <?php endif?>
                            </fieldset>
                        </div>

                         <div id="manage-courses" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Manage Courses</legend>
                                <?php if ($unique_course_listing_dataprovider): ?>
                                    <p>Click one of the following links to download the course listing.</p>
                                        <?= ExportMenu::widget([
                                            'dataProvider' => $unique_course_listing_dataprovider,
                                            'columns' => 
                                                [
                                                    [
                                                        'attribute' => 'coursecode',
                                                        'format' => 'text',
                                                        'label' => 'Course Code'
                                                    ],
                                                    [
                                                        'attribute' => 'name',
                                                        'format' => 'text',
                                                        'label' => 'Course Name'
                                                    ],
                                                ],
                                                'fontAwesome' => true,
                                                'dropdownOptions' => [
                                                    'label' => 'Select Export Type',
                                                    'class' => 'btn btn-default'
                                                ],
                                                'asDropdown' => false,
                                                'showColumnSelector' => false,
                                                'filename' => $unique_listing_filename,
                                                'exportConfig' => [
//                                                     ExportMenu::FORMAT_PDF => false,
                                                    ExportMenu::FORMAT_TEXT => false,
                                                    ExportMenu::FORMAT_HTML => false,
                                                    ExportMenu::FORMAT_EXCEL => false,
                                                    ExportMenu::FORMAT_EXCEL_X => false
                                                ],
                                            ]);
                                        ?>
                                        <br/>
                                <?php endif?>
                                
                                <?php if ($course_details_dataprovider) : ?>
                                    <?= $this->render('course_details_results', [
                                                                'dataProvider' => $course_details_dataprovider,
                                                                'academicofferingid' => $academicofferingid
                                                                 ])
                                    ?>
                                <?php endif?>
                                
                                <?php if ($cape_course_details_dataprovider) : ?>
                                    <?= $this->render('cape_course_details_results', [
                                                                'dataProvider' => $cape_course_details_dataprovider,
                                                                'academicofferingid' => $academicofferingid
                                                            ])
                                   ?>
                                 <?php endif?>
                            </fieldset>
                        </div>

                         <div id="intake-reports" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Intake Reports</legend>
                                <p>Select the button below to generate the intake report.</p>
                                <a class="btn btn-success glyphicon glyphicon-list-alt" style="width:20%; margin:0 auto;" 
                                    href=<?=Url::toRoute(['/subcomponents/programmes/programmes/generate-intake-report',
                                                                            'academicofferingid' => $academicofferingid
                                                                        ]);
                                                ?> role="button"> Generate Report
                                </a>
                            </fieldset>
                        </div>

                         <div id="student-performance-reports" style="display:none">
                             <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Student Performance</legend>
                                <p>
                                    <strong>1.</strong> Select the button below to generate a report that summaries the overall performance of students 
                                    enrolled in a particular course.
                                </p>
                                <a class="btn btn-success glyphicon glyphicon-list-alt" style="width:30%; margin:0 auto;" 
                                    href=<?=Url::toRoute(['/subcomponents/programmes/programmes/generate-programme-broadsheet',
                                                                            'academicofferingid' => $academicofferingid
                                                                        ]);
                                                ?> role="button"> Generate Programme Summary
                                </a><br/><br/><br/>
                                        
                                <?php if($broadsheet_dataprovider  && $iscape == false):?>
                                        <p><strong>2.</strong>Click on the following links to download a detailed ASc. programme broadsheet in the format of your choice</p>
                                        <?= ExportMenu::widget([
                                                'dataProvider' => $broadsheet_dataprovider,
                                                'columns' => [
                                                        [
                                                            'attribute' => 'studentid',
                                                            'format' => 'text',
                                                            'label' => 'Student ID'
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
                                                            'attribute' => 'lastname',
                                                            'format' => 'text',
                                                            'label' => 'Last Name'
                                                        ],
                                                        [
                                                            'attribute' => 'coursecode',
                                                            'format' => 'text',
                                                            'label' => 'Course Code'
                                                        ],
                                                        [
                                                            'attribute' => 'coursename',
                                                            'format' => 'text',
                                                            'label' => 'Course Name'
                                                        ],
                                                        [
                                                            'attribute' => 'semester',
                                                            'format' => 'text',
                                                            'label' => 'Semester'
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
                                                            'attribute' => 'final',
                                                            'format' => 'text',
                                                            'label' => 'Final'
                                                        ],
                                                        [
                                                            'attribute' => 'grade',
                                                            'format' => 'text',
                                                            'label' => 'Grade'
                                                        ],
                                                        [
                                                            'attribute' => 'status',
                                                            'format' => 'text',
                                                            'label' => 'Status'
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
                                                'filename' => $filename,
                                                'exportConfig' => [
                                                     ExportMenu::FORMAT_PDF => false,
                                                    ExportMenu::FORMAT_TEXT => false,
                                                    ExportMenu::FORMAT_HTML => false,
                                                    ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                                ],
                                            ]);
                                        ?>
                                <?php elseif($broadsheet_dataprovider  && $iscape == true):?>
                                    <p><strong>2.</strong>Click on the following links to download a detailed CAPE programme broadsheet in the format of your choice</p>
                                    <?= ExportMenu::widget([
                                            'dataProvider' => $broadsheet_dataprovider,
                                            'columns' => [
                                                    [
                                                        'attribute' => 'studentid',
                                                        'format' => 'text',
                                                        'label' => 'Student ID'
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
                                                        'attribute' => 'lastname',
                                                        'format' => 'text',
                                                        'label' => 'Last Name'
                                                    ],
                                                    [
                                                        'attribute' => 'coursecode',
                                                        'format' => 'text',
                                                        'label' => 'Course Code'
                                                    ],
                                                    [
                                                        'attribute' => 'coursename',
                                                        'format' => 'text',
                                                        'label' => 'Course Name'
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
                                                        'attribute' => 'final',
                                                        'format' => 'text',
                                                        'label' => 'Final'
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
                                            'filename' => $filename,
                                            'exportConfig' => [
                                                 ExportMenu::FORMAT_PDF => false,
                                                ExportMenu::FORMAT_TEXT => false,
                                                ExportMenu::FORMAT_HTML => false,
                                                ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                            ],
                                        ]);
                                    ?>
                                <?php endif;?>
                                    
                                    
                                <?php if($cumulative_grade_dataprovider  && $iscape == false):?>
                                        <br/><br/>
                                        <p>
                                            <strong>3.</strong>Click on any of the following links to download a student listing of all the enrolled 
                                            students within this ASc. programme. The primary focus of this report is a student's current cumulative
                                            academic performance.
                                        </p>
                                        <?= ExportMenu::widget([
                                                'dataProvider' => $cumulative_grade_dataprovider,
                                                'columns' => [
                                                        [
                                                            'attribute' => 'studentid',
                                                            'format' => 'text',
                                                            'label' => 'Student ID'
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
                                                            'attribute' => 'lastname',
                                                            'format' => 'text',
                                                            'label' => 'Last Name'
                                                        ],
                                                        [
                                                            'attribute' => 'final',
                                                            'format' => 'text',
                                                            'label' => 'Cumulative GPA'
                                                        ],
                                                    ],
                                                'fontAwesome' => true,
                                                'dropdownOptions' => [
                                                    'label' => 'Select Export Type',
                                                    'class' => 'btn btn-default'
                                                ],
                                                'asDropdown' => false,
                                                'showColumnSelector' => false,
                                                'filename' => $cumulative_grade_filename,
                                                'exportConfig' => [
                                                     ExportMenu::FORMAT_PDF => false,
                                                    ExportMenu::FORMAT_TEXT => false,
                                                    ExportMenu::FORMAT_HTML => false,
                                                    ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                                ],
                                            ]);
                                        ?>
                                    <?php endif;?>
                                        
                                        
                                    <?php if($programme_comparison_dataprovider  && $iscape == false):?>
                                        <br/><br/>
                                        <p>
                                            <strong>4.</strong>Click on any of the following links to download a report showing the current top 
                                            performers from each ASc. Programme.
                                        </p>
                                        <?= ExportMenu::widget([
                                                'dataProvider' => $programme_comparison_dataprovider,
                                                'columns' => [
                                                         [
                                                            'attribute' => 'division',
                                                            'format' => 'text',
                                                            'label' => 'Division'
                                                        ],
                                                        [
                                                            'attribute' => 'programme',
                                                            'format' => 'text',
                                                            'label' => 'Programme'
                                                        ],
                                                        [
                                                            'attribute' => 'studentid',
                                                            'format' => 'text',
                                                            'label' => 'Student ID'
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
                                                            'attribute' => 'lastname',
                                                            'format' => 'text',
                                                            'label' => 'Last Name'
                                                        ],
                                                        [
                                                            'attribute' => 'final',
                                                            'format' => 'text',
                                                            'label' => 'Cumulative GPA'
                                                        ],
                                                    ],
                                                'fontAwesome' => true,
                                                'dropdownOptions' => [
                                                    'label' => 'Select Export Type',
                                                    'class' => 'btn btn-default'
                                                ],
                                                'asDropdown' => false,
                                                'showColumnSelector' => false,
                                                'filename' => $cumulative_grade_filename,
                                                'exportConfig' => [
//                                                    ExportMenu::FORMAT_PDF => false,
                                                    ExportMenu::FORMAT_TEXT => false,
                                                    ExportMenu::FORMAT_HTML => false,
                                                    ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                                ],
                                            ]);
                                        ?>
                                    <?php endif;?>
                            </fieldset>
                        </div>
                    </div>
            </div>
         </div>
     </div>

