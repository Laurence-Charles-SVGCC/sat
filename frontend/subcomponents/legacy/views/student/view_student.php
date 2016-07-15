<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    use yii\helpers\Html;
    use yii\helpers\Url;

     $this->title = 'Student Profile';
     $this->params['breadcrumbs'][] = ['label' => 'Student Search', 'url' => ['find-a-student']];
     $this->params['breadcrumbs'][] = $this->title;
     
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/legacy/legacy/index']);?>" title="Manage Legacy Records">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/legacy.png" alt="legacy avatar">
                <span class="custom_module_label" > Welcome to the Legacy Management System</span> 
                <img src ="css/dist/img/header_images/legacy.png" alt="legacy avatar" class="pull-right">
            </a>  
        </div>
        
        
        <div class="custom_body">  
            <h1 class="custom_h1"><?=$this->title;?></h1>
           
            <br/>
            <div role="tabpanel" class="tab-pane fade in active" id="legacy-general"> 
                <br/>
                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">General</div>
                    <table class="table table-hover" style="margin: 0 auto;">
                        <tr>
                            <td rowspan="2"> 
                                <?php if (strcasecmp($student->gender, "Male") == 0): ?>
                                    <img src="css/dist/img/avatar_male(150_150).png" alt="avatar_male" class="img-rounded">
                                <?php elseif (strcasecmp($student->gender, "Female") == 0): ?>
                                    <img src="css/dist/img/avatar_female(150_150).png" alt="avatar_female" class="img-rounded">
                                <?php endif;?>
                            </td>
                            <th>Full Name</th>
                            <td><?=$student->title . ". " . $student->firstname . " " . $student->middlename . " " . $student->lastname ;?></td>
                            <th>Gender</th>
                            <td><?=$student->gender;?></td>
                        </tr>

                        <tr>
                            <th>Faculty</th>
                            <td><?=$faculty ;?></td>
                            <th>Admission Year</th>
                            <td><?=$admission_year;?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <br/>
            <h2 class="custom_h2">Transcript</h2>
            <?php if($records == false):?>
                <h3>No records found</h3>
            <?php else:?>
                <div role="tabpanel" class="tab-pane fade in active" id="legacy-transcript"> 
                    <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                        <?php foreach($records as $record):?>
                            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em"><?=$record["name"];?></div>
                            <table class="table table-hover" style="margin: 0 auto;">
                                <?php foreach($record["details"] as $term):?>
                                    <tr>
                                       <th colspan='7' style="color:lightgreen;font-weight:bold; font-size:1.2em"><?=$term["name"]?></th>
                                    </tr>
                                    <?php foreach($term["details"] as $subject):?>
                                        <tr>
                                            <th><?=$subject["name"]?></th>
                                            <th>Term</>
                                            <td><?=$subject["details"]["term"]?></th>
                                            <th>Exam</>
                                            <td><?=$subject["details"]["exam"]?></th>
                                            <th>Final</>
                                            <td><?=$subject["details"]["final"]?></th>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endforeach;?>
                            </table>

                        <?php endforeach;?>
                    </div>
                    <br/>
                <?php endif;?>
            </div>
            <br/>
        </div>
    </div>
</div>