<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    use frontend\models\CourseOutline;
    use frontend\models\CourseOffering;
    
    $this->title = 'Course Management';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview',
                                                            'programmecatalogid' => $programmecatalogid
                                                            ])];
    $this->params['breadcrumbs'][] = ['label' => 'Academic Offering Overview', 'url' => Url::to(['programmes/get-academic-offering',
                                                            'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid
                                                            ])];
    $this->params['breadcrumbs'][] = $this->title;
    
    $menu_items = [
        1 => "Manage Programme Booklets",
        2 => "View Course Details",
        3 => "View Intake Reports",
        4 => "View Performance Report",
    ];
?>


<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <?php if($asc_data):?>
        <div id="asc-overview" style='width: 98%; margin: 0 auto;'>
            <fieldset style="width:100%">
                <legend><strong>Course Description</strong></legend>
                <table class='table table-hover'>
                    <tr>
                        <th>Code</th>
                        <td><?=$asc_data[0]['code'];?></td>
                        <th>Name</th>
                        <td><?=$asc_data[0]['name'];?></td>
                        <th>Semester</th>
                        <td><?=$asc_data[0]['semester'];?></td>
                    </tr>

                     <tr>
                         <th>Course Type</th>
                        <td><?=$asc_data[0]['coursetype'];?></td>
                        <th>Pass Criteria</th>
                        <td><?=$asc_data[0]['passcriteria'];?></td>
                        <th>GPA Consideration</th>
                        <td><?=$asc_data[0]['passfailtype'];?></td>
                    </tr>

                    <tr>
                        <th>Coursework Weight (%)</th>
                        <td><?=$asc_data[0]['coursework'];?></td>
                        <th>Exam Weight (%)</th>
                        <td><?=$asc_data[0]['exam'];?></td>
                        <th>Credits</th>
                        <td><?=$asc_data[0]['credits'];?></td>
                    </tr>

                    <tr>
                        <th>Lecturers</th>
                        <td><?=$asc_data[0]['lecturer'];?></td>
                        <th>No. of Batches</th>
                        <td><?=$asc_data[0]['batches'];?></td>
                        <th>Actions</th>
                        <?php
                            echo "<td>";                                  
                                echo "<div class='dropdown'>
                                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                    echo "Select Action...";
                                    echo "<span class='caret'></span>";
                                    echo "</button>";
                                    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                        $edit_course_offering_link = Url::toRoute(['/subcomponents/programmes/programmes/edit-course-offering/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        $add_course_outline_link = Url::toRoute(['/subcomponents/programmes/programmes/add-course-outline/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        $edit_course_outline_link = Url::toRoute(['/subcomponents/programmes/programmes/edit-course-outline/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        echo "<li><a href='$edit_course_offering_link'>Edit Course Offering</a></li>";  
                                        if(CourseOutline::getSpecificOutline($code) == false)
                                        {
                                            echo "<li><a href='$add_course_outline_link'>Add Course Outline</a></li>"; 
                                        }
                                        else
                                        {
                                             echo "<li><a href='$edit_course_outline_link'>Edit Course Outline</a></li>"; 
                                        }
                                    echo "</ul>";
                                echo "</div>";
                            echo "</td>"; 
                        ?>
                    </tr>
                </table>
            </fieldset><br/><br/>

            <fieldset>
                <legend><strong>Performance Report</strong></legend>
                <p>The following table presents a summation of grades attained by students enrolled in this course.</p>
                <table class='table table-striped'>
                    <tr>
                        <th>No. of Passes</th>
                        <td><?=$asc_data[0]['passes'];?></td>
                        <th>No .of Fails</th>
                        <td><?=$asc_data[0]['fails'];?></td>
                        <th>No .of Students</th>
                        <td><?=$asc_data[0]['total'];?></td>
                    </tr>

                    <tr>
                        <th>A+'s</th>
                        <td><?=$asc_data[0]['a_plus'];?></td>
                        <th>A's</th>
                        <td><?=$asc_data[0]['a'];?></td>
                        <th>A-'s</th>
                        <td><?=$asc_data[0]['a_minus'];?></td>
                    </tr>

                    <tr>
                        <th>B+'s</th>
                        <td><?=$asc_data[0]['b_plus'];?></td>
                        <th>B's</th>
                        <td><?=$asc_data[0]['b'];?></td>
                        <th>B-'s</th>
                        <td><?=$asc_data[0]['b_minus'];?></td>
                    </tr>

                    <tr>
                        <th>C+'s</th>
                        <td><?=$asc_data[0]['c_plus'];?></td>
                        <th>C's</th>
                        <td><?=$asc_data[0]['c'];?></td>
                        <th>C-'s</th>
                        <td><?=$asc_data[0]['c_minus'];?></td>
                    </tr>

                    <tr>
                        <th>D's</th>
                        <td><?=$asc_data[0]['d'];?></td>
                        <th>Modal Grade</th>
                        <td colspan="3"><?=$asc_data[0]['mode'];?></td>
                    </tr>
                </table>
            </fieldset><br/>

             <fieldset>
                <legend><strong>Batch Selection</strong></legend>
                <?php if($asc_batches):?>
                   <p>If you wish to investigate a particular batch, click on the associated link.</p>
                   <ul>
                       <?php foreach($asc_batches as $batch):?>
                       <li>
                           <?=Html::a($batch['name'], 
                                       Url::to(['programmes/batch-management', 'batchid' => $batch['batchid'],  'iscape' => 0,  'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid,  'code' => $batch['course']])); 
                           ?>
                       </li>
                       <?php endforeach;?>
                   </ul>
               <?php else:?>
                   <p>No batches have been created for this course</p>
               <?php endif;?>
            </fieldset>
        </div>
    <?php endif;?>

    <?php if($cape_data):?>
        <div id="cape-overview" style='width: 98%; margin: 0 auto;'>
            <fieldset>
                <legend><strong>Course Description</strong></legend>
                <table class='table table-hover'>
                    <tr>
                        <th>Code</th>
                        <td><?=$cape_data[0]['code'];?></td>
                        <th>Name</th>
                        <td><?=$cape_data[0]['name'];?></td>
                        <th>Subject</th>
                        <td><?=$cape_data[0]['subject'];?></td>
                    </tr>

                     <tr>
                         <th>Semester</th>
                        <td><?=$cape_data[0]['semester'];?></td>
                        <th>Coursework Weight (%)</th>
                        <td><?=$cape_data[0]['coursework'];?></td>
                        <th>Exam Weight (%)</th>
                        <td><?=$cape_data[0]['exam'];?></td>
                    </tr>

                    <tr>
                        <th>Lecturers</th>
                        <td><?=$cape_data[0]['lecturer'];?></td>
                        <th>No. of Batches</th>
                        <td><?=$cape_data[0]['batches'];?></td>
                        <th>Actions</th>
                        <?php
                            echo "<td>";                                  
                                echo "<div class='dropdown'>
                                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                    echo "Select Action...";
                                    echo "<span class='caret'></span>";
                                    echo "</button>";
                                    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                        $edit_course_offering_link = Url::toRoute(['/subcomponents/programmes/programmes/edit-course-offering/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        $add_course_outline_link = Url::toRoute(['/subcomponents/programmes/programmes/add-course-outline/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        $edit_course_outline_link = Url::toRoute(['/subcomponents/programmes/programmes/edit-course-outline/',
                                                                                    'iscape' => $iscape,
                                                                                    'code' => $code,
                                                                                    'programmecatalogid' => $programmecatalogid,
                                                                                    'academicofferingid' => $academicofferingid,
                                                                                 ]);
                                        echo "<li><a href='$edit_course_offering_link'>Edit Course Offering</a></li>";  
//                                                    
                                        if(CourseOutline::getSpecificOutline($code) == false)
                                        {
                                            echo "<li><a href='$add_course_outline_link'>Add Course Outline</a></li>"; 
                                        }
                                        else
                                        {
                                             echo "<li><a href='$edit_course_outline_link'>Edit Course Outline</a></li>"; 
                                        }
                                    echo "</ul>";
                                echo "</div>";
                            echo "</td>"; 
                        ?>
                    </tr>
                </table>
            </fieldset><br/>

             <fieldset>
                <legend><strong>Performance Report</strong></legend>
                <p>The following table presents a summation of grades attained by students enrolled in this course.</p><br/>
                <table class='table table-striped'>
                    <tr>
                        <th>No .of Passes</th>
                        <td><?=$cape_data[0]['passes'];?></td>
                        <th>No .of Fails</th>
                        <td><?=$cape_data[0]['fails'];?></td>
                        <th>No .of Students</th>
                        <td><?=$cape_data[0]['total'];?></td>
                    </tr>

                    <tr>
                        <th>>=90</th>
                        <td><?=$cape_data[0]['ninety_plus'];?></td>
                        <th>80-90</th>
                        <td><?=$cape_data[0]['eighty_to_ninety'];?></td>
                        <th>70-80</th>
                        <td><?=$cape_data[0]['seventy_to_eighty'];?></td>
                    </tr>

                    <tr>
                        <th>60-70</th>
                        <td><?=$cape_data[0]['sixty_to_seventy'];?></td>
                        <th>50-60</th>
                        <td><?=$cape_data[0]['fifty_to_sixty'];?></td>
                        <th>40-50</th>
                        <td><?=$cape_data[0]['forty_to_fifty'];?></td>
                    </tr>

                    <tr>
                        <th>35-40</th>
                        <td><?=$cape_data[0]['thirtyfive_to_forty'];?></td>
                        <th><35</th>
                        <td><?=$cape_data[0]['minus_thirtyfive'];?></td>
                        <th>Modal Grade</th>
                        <td><?=$cape_data[0]['mode'];?></td>
                    </tr>
                </table>
            </fieldset><br/>

            <fieldset>
                <legend><strong>Batch Selection</strong></legend>
                <?php if($cape_batches):?>
                   <p>If you wish to investigate a particular batch, click on the associated link.</p>
                   <ul>
                       <?php foreach($cape_batches as $batch):?>
                       <li>
                           <?=Html::a($batch['name'], 
                                       Url::to(['programmes/batch-management', 'batchid' => $batch['batchid'], 'iscape' => 1,  'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid,  'code' => $batch['course']])); 
                           ?>
                       </li>
                       <?php endforeach;?>
                   </ul>
               <?php else:?>
                   <p>No batches have been created for this course</p>
               <?php endif;?>
            </fieldset>
        </div>
    <?php endif;?><br/>
</div>