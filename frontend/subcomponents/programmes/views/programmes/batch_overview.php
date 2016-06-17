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
    
    $this->title = 'Batch Management Dashboard';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/course-management',
                                                            'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Course Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'isacpe' => $iscape, 'programmecatalogid' => $programmecatalogid, 
                                                            'academicofferingid' => $academicofferingid, 'code' => $code
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
                <h1 class="custom_h1"><?=$batch_name?></h1>
                <br/>
                    
                <?php if($iscape==0 && $students_dataprovider!=false):?>
                    <div id="asc-overview" style='width: 95%; margin: 0 auto;'>
                        <fieldset>
                            <legend class="custom_h2_a">Batch Performance Report</legend>
                            <p>The following table presents a summation of grades attained by students enrolled in this batch.</p>
                            <table class='table table-striped'>
                                <tr>
                                    <th>Course Code</th>
                                    <td><?=$batch_info['coursecode'];?></td>
                                    <th>Batch Name</th>
                                    <td><?=$batch_info['name'];?></td>
                                    <th>Lecturers</th>
                                    <td><?=$batch_info['lecturer'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>No. of Passes</th>
                                    <td><?=$batch_info['passes'];?></td>
                                    <th>No .of Fails</th>
                                    <td><?=$batch_info['fails'];?></td>
                                    <th>No .of Students</th>
                                    <td><?=$batch_info['total'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>A+'s</th>
                                    <td><?=$batch_info['a_plus'];?></td>
                                    <th>A's</th>
                                    <td><?=$batch_info['a'];?></td>
                                    <th>A-'s</th>
                                    <td><?=$batch_info['a_minus'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>B+'s</th>
                                    <td><?=$batch_info['b_plus'];?></td>
                                    <th>B's</th>
                                    <td><?=$batch_info['b'];?></td>
                                    <th>B-'s</th>
                                    <td><?=$batch_info['b_minus'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>C+'s</th>
                                    <td><?=$batch_info['c_plus'];?></td>
                                    <th>C's</th>
                                    <td><?=$batch_info['c'];?></td>
                                    <th>C-'s</th>
                                    <td><?=$batch_info['c_minus'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>D's</th>
                                    <td><?=$batch_info['d'];?></td>
                                    <th>Modal Grade</th>
                                    <td colspan="3"><?=$batch_info['mode'];?></td>
                                </tr>
                            </table>
                        </fieldset><br/>
                        
                         <fieldset>
                            <legend class="custom_h2_a">Student Performance Report</legend>
                             <p>Click on the following links to download a copy of the following report.</p>
                             <?= ExportMenu::widget([
                                    'dataProvider' => $students_dataprovider,
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
//                                                     ExportMenu::FORMAT_PDF => false,
                                        ExportMenu::FORMAT_TEXT => false,
                                        ExportMenu::FORMAT_HTML => false,
                                        ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                    ],
                                ]);
                            ?><br/><br/>
                            
                            <?= $this->render('asc_batch_students', [
                                        'dataProvider' =>  $students_dataprovider,
                                    ]) 
                            ?>
                        </fieldset>
                    </div>
                
                <?php elseif($iscape==1  && $students_dataprovider!=false):?>
                    <div id="cape-overview" style='width: 95%; margin: 0 auto;'>
                         <fieldset>
                            <legend class="custom_h2_a">Batch Performance Report</legend>
                            <p>The following table presents a summation of grades attained by students enrolled in this batch.</p><br/>
                            <table class='table table-striped'>
                                <tr>
                                    <th>Subject</th>
                                    <td><?=$batch_info['subject'];?></td>
                                    <th>Course Code</th>
                                    <td><?=$batch_info['coursecode'];?></td>
                                    <th>Batch Name</th>
                                    <td><?=$batch_info['name'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>Lecturers</th>
                                    <td colspan="5"><?=$batch_info['lecturer'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>No .of Passes</th>
                                    <td><?=$batch_info['passes'];?></td>
                                    <th>No .of Fails</th>
                                    <td><?=$batch_info['fails'];?></td>
                                    <th>No .of Students</th>
                                    <td><?=$batch_info['total'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>>=90</th>
                                    <td><?=$batch_info['ninety_plus'];?></td>
                                    <th>80-90</th>
                                    <td><?=$batch_info['eighty_to_ninety'];?></td>
                                    <th>70-80</th>
                                    <td><?=$batch_info['seventy_to_eighty'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>60-70</th>
                                    <td><?=$batch_info['sixty_to_seventy'];?></td>
                                    <th>50-60</th>
                                    <td><?=$batch_info['fifty_to_sixty'];?></td>
                                    <th>40-50</th>
                                    <td><?=$batch_info['forty_to_fifty'];?></td>
                                </tr>
                                
                                <tr>
                                    <th>35-40</th>
                                    <td><?=$batch_info['thirtyfive_to_forty'];?></td>
                                    <th><35</th>
                                    <td><?=$batch_info['minus_thirtyfive'];?></td>
                                    <th>Modal Grade</th>
                                    <td><?=$batch_info['mode'];?></td>
                                </tr>
                            </table>
                        </fieldset><br/>
                        
                        <fieldset>
                            <legend class="custom_h2_a">Student Performance Report</legend>
                            <p>Click on the following links to download a copy of the following report.</p>
                                <?= ExportMenu::widget([
                                    'dataProvider' =>$students_dataprovider,
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
//                                                 ExportMenu::FORMAT_PDF => false,
                                        ExportMenu::FORMAT_TEXT => false,
                                        ExportMenu::FORMAT_HTML => false,
                                        ExportMenu::FORMAT_EXCEL => false,
//                                                    ExportMenu::FORMAT_EXCEL_X => false
                                    ],
                                ]);
                            ?><br/><br/>
                            
                            <?= $this->render('cape_batch_students', [
                                        'dataProvider' =>  $students_dataprovider,
                                    ]) 
                            ?>
                            
                        </fieldset>
                    </div>
                
                <?php else:?>
                    <h3>Grades have not been entered for this batch.</h3>
                <?php endif;?>
            </div>
         </div>
     </div>



