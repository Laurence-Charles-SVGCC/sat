<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
    
    $this->title = 'Course Management Dashboard';
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
                    
                <?php if($asc_data):?>
                    <div id="asc-overview" style='width: 95%; margin: 0 auto;'>
                        <fieldset style="width:100%">
                            <legend class="custom_h2_a" >Course Description</legend>
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
                                    <td>N/A</td>
                                </tr>
                            </table>
                        </fieldset><br/><br/>
                        
                        <fieldset>
                            <legend class="custom_h2_a">Performance Report</legend>
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
                            <legend class="custom_h2_a">Batch Selection</legend>
                            <?php if($asc_batches):?>
                               <p>If you wish to investigate a particular batch, click on the associated link.</p>
                               <ul>
                                   <?php foreach($asc_batches as $batch):?>
                                   <li>
                                       <?=Html::a($batch['name'], 
                                                   Url::to(['programmes/batch-management', 'iscape' => 0, 'batchid' => $batch['batchid'], 'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid,  'code' => $batch['course']])); 
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
                    <div id="cape-overview" style='width: 95%; margin: 0 auto;'>
                        <fieldset>
                            <legend class="custom_h2_a">Course Description</legend>
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
                                    <td>N/A</td>
                                </tr>
                            </table>
                        </fieldset><br/>
                        
                         <fieldset>
                            <legend class="custom_h2_a">Performance Report</legend>
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
                            <legend class="custom_h2_a">Batch Selection</legend>
                            <?php if($cape_batches):?>
                               <p>If you wish to investigate a particular batch, click on the associated link.</p>
                               <ul>
                                   <?php foreach($cape_batches as $batch):?>
                                   <li>
                                       <?=Html::a($batch['name'], 
                                                   Url::to(['programmes/batch-management', 'iscape' => 0, 'batchid' => $batch['batchid'], 'programmecatalogid' => $programmecatalogid, 'academicofferingid' => $academicofferingid,  'code' => $batch['course']])); 
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
            </div>
         </div>
     </div>



