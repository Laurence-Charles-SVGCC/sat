<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Academic Offering Overview';
     $this->params['breadcrumbs'][] = ['label' => 'Programme Overview', 'url' => Url::to(['programmes/programme-overview'])];
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $menu_items = [
        1 => "Manage Programme Booklets",
        2 => "Manage Courses",
        3 => "View Intake Reports",
        4 => "View Performance Reports",
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
                                <?php if(true):?>
                                    <a class="btn btn-info glyphicon glyphicon-download-alt" style="width:20%; margin-left:5%; margin-right:15%"
                                            href=<?=Url::toRoute(['/subcomponents/programmes/programmes/download-booklet', 
                                                                                'divisionid' => $programme_info['divisionid'],
                                                                                'programmecatalogid' => $programme_info['programmecatalogid'],
                                                                                'academicofferingid' => $academicofferingid,]);
                                                    ?> role="button"> Download Booklet
                                    </a>
                                     <a class="btn btn-info glyphicon glyphicon-remove" style="width:20%; margin-right:15%" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/delete-booklet']);?> role="button"> Delete Booklet</a>
                                     <a class="btn btn-info glyphicon glyphicon-refresh" style="width:20%;" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/replace-booklet']);?> role="button"> Replace Booklet</a>
                                <?php else:?>
                                     <a class="btn btn-info glyphicon glyphicon-plus" style="width:20%; margin:0 auto;" href=<?=Url::toRoute(['/subcomponents/programmes/programmes/upload-booklet']);?> role="button"> Upload Booklet</a>
                                <?php endif?>
                            </fieldset>
                        </div>

                         <div id="manage-courses" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Manage Courses</legend>
                            </fieldset>
                        </div>

                         <div id="intake-reports" style="display:none">
                            <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Intake Reports</legend>
                            </fieldset>
                        </div>

                         <div id="student-performance-reports" style="display:none">
                             <fieldset>
                                <legend class="custom_h2" style="margin-left:0%;">Student Performance</legend>
                            </fieldset>
                        </div>
                    </div>
            </div>
         </div>
     </div>

