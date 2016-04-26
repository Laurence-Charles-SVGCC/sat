<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Award;
    use frontend\models\AwardCategory;
    use frontend\models\AwardScope;
    use frontend\models\AwardType;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Division;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Awards Control Panel';
?>


    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/registry/awards/manage-awards']);?>" title="Manage Awards">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/award.png');?>" alt="award avatar">
                    <span class="custom_module_label" style="margin-left:5%;"> Welcome to the Award Management System</span> 
                    <img src ="<?=Url::to('../images/award.png');?>" alt="award avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                </br>                              
                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Awards Listing
                        <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/registry/awards/configure-award', 'action' => 'create']);?> role="button"> Create Award</a>
                    </div>
                    
                    <?php if($awards == false):?>
                        <h3>No awards have been created</h3>
                    <?php else:?>
                        <table class='table table-condensed' style='margin: 0 auto;'>
                            <?php foreach($awards as $award):?>
                                <tr>
                                    <th rowspan='5' style='vertical-align:top; text-align:center; font-size:1.2em;'><?=$award->name?></th>
                                    <th>Description</th>
                                    <td colspan="3"><?=$award->description?></td>
                                </tr>
                                
                                <tr>
                                    <th>Category</th>
                                    <td ><?=AwardCategory::find()->where(['awardcategoryid' => $award->awardcategoryid])->one()->name;?></td>
                                    
                                    <th>Type</th>
                                    <td><?=AwardType::find()->where(['awardtypeid' => $award->awardtypeid])->one()->name;?></td>
                                </tr>
                                
                                <tr>
                                    <?php if ($award->awardtypeid == 1):?>
                                        <th>Semester</th>
                                        <td><?=Semester::find()->where(['semesterid' => $award->semesterid])->one()->title;?></td>
                                    <?php elseif ($award->awardtypeid == 2):?>
                                        <th>Academic Year</th>
                                        <td><?=AcademicYear::find()->where(['academicyearid' => $award->academicyearid])->one()->title;?></td>
                                    <?php endif;?>
                                        
                                    <th>Scope</th>
                                    <td><?=AwardScope::find()->where(['awardscopeid' => $award->awardscopeid])->one()->name;?></td>
                                </tr>
                                
                                <tr>
                                    <?php if ($award->awardscopeid == 2):?>
                                        <th>Division</th>
                                        <td><?=Division::find()->where(['divisionid' => $award->divisionid])->one()->abbreviation;?></td>
                                    <?php elseif ($award->awardtypeid == 3):?>
                                        <th>Department</th>
                                        <td><?=Department::find()->where(['departmentid' => $award->departmentid])->one()->name;?></td>
                                    <?php elseif ($award->awardtypeid == 4):?>
                                        <th>Programme</th>
                                        <td><?=ProgrammeCatalog::find()->where(['prgrammecatalogid' => $award->academicyearid])->one()->name;?></td>
                                    <?php elseif ($award->awardtypeid == 5):?>
                                        <th>Subject</th>
                                        <td><?=$award->subject?></td>
                                    <?php endif;?>
                                    
                                    <th>Action</th>
                                    <td>
                                        <div class='dropdown'>
                                            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                                Select your intended action
                                                <span class='caret'></span>
                                            </button>
                                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                                <li><a href=<?=Url::toRoute(['/subcomponents/registry/awards/configure-award', 'action' => 'edit', 'recordid' => $award->awardid])?>>Edit</a></li>
                                                <?php if(Award::getAwardees($award->awardid) == true):?>   
                                                    <li><a href=<?=Url::toRoute(['/subcomponents/registry/awards/view-awardees', 'recordid' => $award->awardid])?>>View Awardees</a></li>
                                                <?php endif;?>
                                                <?php if(Award::isAssigned($award->awardid) == false):?>    
                                                    <li><a href=<?=Url::toRoute(['/subcomponents/registry/awards/delete-award', 'recordid' => $award->awardid])?>>Delete Award</a></li>
                                                <?php endif;?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

