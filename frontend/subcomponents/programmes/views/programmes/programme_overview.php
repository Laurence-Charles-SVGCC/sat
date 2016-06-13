<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Programme Overview';
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $menu_items = [
         1 => "View Course Outlines",
         2 => "Investigate Academic Year",
    ];
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
                <h1 class="custom_h1"><?=$programme_name?></h1>
                <br/>
                
                    <div id="summary">
                        <?php if(!$programme_info):?>
                            <h3>Unable to retrieve programme summary</h3>
                        <?php else:?>
                            <table class='table table-hover' style='width: 90%; margin: 0 auto;'>
                                <tr>
                                    <th class="custom_h2" colspan="6" style="text-align:center; color:green">Programme Summary</th>
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
                                    <td colspan="3"><?=$programme_info['department'];?></td>
                                    <th> Download Progamme Booklet</th>
                                    <?php
                                        echo "<td>";                                  
                                            echo "<div class='dropdown'>
                                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Select Cohort...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                    $cohort_count = $cohort_array[0];
                                                    if ($cohort_count > 0)
                                                    {
                                                        for ($k = 1 ; $k <= $cohort_count ; $k++)
                                                        {
                                                            $year_title = AcademicYear::getYearTitle($cohort_array[$k]->academicyearid);
                                                            $academic_year_id = $cohort_array[$k]->academicyearid;
                                                            $academic_offering_id = $cohort_array[$k]->academicofferingid;
                                                            $year_title = AcademicYear::getYearTitle($academic_year_id);
                                                            $divisionid = Department::getDivisionID($programme['departmentid']);
                                                            $hyperlink = Url::toRoute(['/subcomponents/programmes/programmes/download-booklet/', 
                                                                                                'divisionid' => $divisionid,
                                                                                                'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                                'academicofferingid' => $academic_offering_id,
                                                                                             ]);
                                                            if(ProgrammeCatalog::getBooklets($divisionid, $programme_info['programmecatalogid'],  $academic_offering_id)==true)
                                                                echo "<li><a href='$hyperlink'>$year_title</a></li>";  
                                                            else
                                                                 echo "<li><a>$year_title - Not Available</a></li>"; 
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<li>This programme has yet to be offered</li>";  
                                                    }    
                                                echo "</ul>";
                                            echo "</div>";
                                        echo "</td>"; 
                                    echo "</td>";
                                    ?>
                                </tr>
                                
                                <tr>
                                    <th>Most Recent Coordinator(s)</th>
                                    <?php if($cordinator_details):?>
                                        <td colspan="5"><?=$cordinator_details?></td>
                                    <?php else:?>
                                        <td colspan="5">No appointees</td>
                                    <?php endif;?>
                                </tr>
                            </table></br>
                         <?php endif;?>
                     </div><br/><br/>
                
                    <div id="options-panel" style='width: 90%; margin: 0 auto;'>
                        <div id="options">
                             Please select one of the following actions:
                             <?= Html::radioList('programme_options', null, $menu_items, [ 'onclick'=> 'toggleProgrammeOptions();',  'style' => 'width: 40%']);?>
                        </div><br/>

                        <div id="view-course-outlines" style="display:none;">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Course Catalog Listing</legend>
                                <p>The following is a list of all the courses associated with the programme in question since it's inception.</p>
                                
                                <p>
                                    If a course outline is avaliable the "CouseCode" field will be an active link.
                                    If this is the case, click on the course code of any course  if you wish to view it's course outline.
                                </p>
                                
                                <p>
                                    If no course outline exists, you will be able to enter/edit course outline using the <strong>
                                    Investigate Academic Year </strong> option from the options listed above.
                                </p><br/>
                                
                                 <?php if ($course_outline_dataprovider) : ?>
                                    <?= $this->render('course_outline_results', [
                                        'dataProvider' => $course_outline_dataprovider,
                                    ]) ?>
                                <?php endif?>
                                
                                <?php if ($cape_course_outline_dataprovider) : ?>
                                    <?= $this->render('cape_course_outline_results', [
                                        'dataProvider' => $cape_course_outline_dataprovider,
                                    ]) ?>
                                <?php endif?>
                            </fieldset>
                        </div>

                         <div id="investigate-academic-year" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Academic Year Selection</legend>
                                <p>
                                    Select the academic year of the programme you wish to investigate.
                                    <?php
                                        echo "<td>";                                  
                                            echo "<div class='dropdown'>
                                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>";
                                                echo "Select Cohort...";
                                                echo "<span class='caret'></span>";
                                                echo "</button>";
                                                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>";
                                                    $cohort_count = $cohort_array[0];
                                                    if ($cohort_count > 0)
                                                    {
                                                        for ($k = 1 ; $k <= $cohort_count ; $k++)
                                                        {
                                                            $year_title = AcademicYear::getYearTitle($cohort_array[$k]->academicyearid);
                                                            $academic_year_id = $cohort_array[$k]->academicyearid;
                                                            $academic_offering_id = $cohort_array[$k]->academicofferingid;
                                                            $divisionid = Department::getDivisionID($programme['departmentid']);
                                                            $hyperlink = Url::toRoute(['/subcomponents/programmes/programmes/get-academic-offering/' ,
                                                                                                'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                                'academicofferingid' => $academic_offering_id,
                                                                                             ]);
                                                            echo "<li><a href='$hyperlink'>$year_title</a></li>";  
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<li>This programme has yet to be offered</li>";  
                                                    }    
                                                echo "</ul>";
                                            echo "</div>";
                                        echo "</td>"; 
                                    echo "</td>";
                                    ?>
                                </p>
                            </fieldset>
                        </div>

                         <div id="view-intake-reports" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Intake Report</legend>
                            </fieldset>
                        </div>

                         <div id="view-student-performance-options" style="display:none">
                             <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Student Performance</legend>
                            </fieldset>
                        </div>
                    </div>
            </div>
         </div>
     </div>

