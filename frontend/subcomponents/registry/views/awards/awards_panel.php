<?php
    use yii\widgets\Breadcrumbs;
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
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <!--</br>-->                              
        <div class="panel panel-default">
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Awards Listing
                <?php if(Yii::$app->user->can('assignAward')):?>
                    <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/registry/awards/configure-award', 'action' => 'create']);?> role="button"> Create Award</a>
                <?php endif;?>
            </div>

            <?php if($awards == false):?>
                <p><strong>No awards have been created</strong></p>
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
                                        <?php if(Yii::$app->user->can('editAward')):?>
                                            <li><a href=<?=Url::toRoute(['/subcomponents/registry/awards/configure-award', 'action' => 'edit', 'recordid' => $award->awardid])?>>Edit</a></li>
                                        <?php endif;?>

                                        <?php if(Award::getAwardees($award->awardid) == true  && Yii::$app->user->can('viewAward')):?>   
                                            <li><a href=<?=Url::toRoute(['/subcomponents/registry/awards/view-awardees', 'recordid' => $award->awardid])?>>View Awardees</a></li>
                                        <?php endif;?>

                                        <?php if(Award::isAssigned($award->awardid) == false && Yii::$app->user->can('deleteAward')):?>    
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