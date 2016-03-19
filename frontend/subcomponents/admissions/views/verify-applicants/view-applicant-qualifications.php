<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use frontend\models\CsecCentre;
use frontend\models\PostSecondaryQualification;
use frontend\models\CsecQualification;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$applicant_name = 'Undefined';
if ($applicant)
{
    $applicant_name = $applicant->firstname . ' ' . $applicant->middlename . ' ' . $applicant->lastname;
}

$this->title = ' Applicant: ' . $applicant_name;
$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];
$this->params['breadcrumbs'][] = $this->title;


?>

<?= Yii::$app->session->getFlash('error'); ?>
<div class="view-applicant-qualifications">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(); ?>
                <br/><fieldset style="width:100%">
                    <legend><strong>Certificate Results</strong></legend>
                    <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                        <thead>
                          <tr>
                            <th>Centre Name</th>
                            <th>Examining Body</th>
                            <th>Candidate #</th>
                            <th>Subject</th>
                            <th>Proficiency</th>
                            <th>Grade</th>
                            <th>Year</th>
                            <th>Verified</th>
                            <th>Queried</th>
                            <th>Delete</th>
                          </tr>
                        </thead>

                        <tbody>
                            <?php
                                $id = $applicant->personid;
                                $csecqualifications = $dataProvider->getModels();
                                $qual_limit = count($csecqualifications);
                            ?>
                            
                            <?= Html::hiddenInput('record_count', $record_count); ?>
                            <?= Html::hiddenInput('qual_limit', $qual_limit); ?>
                            
                            <?php for ($j=0 ; $j<$record_count ; $j++): ?>
                                <?php if($csecqualifications[$j]->cseccentreid != $centreid):?>
                                <tr style="opacity:0.5">
                                <?php else:?>
                                <tr>
                                <?php endif;?>
                                    <?= Html::activeHiddenInput($csecqualifications[$j], "[$j]csecqualificationid"); ?>
                                    <?= Html::activeHiddenInput($csecqualifications[$j], "[$j]personid"); ?>
                                    
                                    <td width = 22.5%>
                                        <?=  $form->field($csecqualifications[$j], "[$j]cseccentreid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                    ArrayHelper::map(CsecCentre::find()->all(), 'cseccentreid', 'name'))?>
                                    </td>
                                   

                                    <td width = 10%>
                                        <?=  $form->field($csecqualifications[$j], "[$j]examinationbodyid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'abbreviation'))?>
                                    </td>

                                    <td width = 10%>
                                        <?= $form->field($csecqualifications[$j], "[$j]candidatenumber", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->textInput(); ?>
                                    </td>
                                    
                                    <td width = 20%> 
                                        <?= $form->field($csecqualifications[$j], "[$j]subjectid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(Subject::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'subjectid', 'name')) ?>
                                    </td>

                                    <td width = 10%>
                                        <?= $form->field($csecqualifications[$j], "[$j]examinationproficiencytypeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationProficiencyType::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'examinationproficiencytypeid', 'name')) ?>
                                    </td>

                                    <td width = 10%> 
                                        <?= $form->field($csecqualifications[$j], "[$j]examinationgradeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationGrade::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'examinationgradeid', 'name')); ?>
                                    </td>

                                    <td width = 7.5%>
                                        <?= $form->field($csecqualifications[$j], "[$j]year", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->textInput(); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($csecqualifications[$j], "[$j]isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($csecqualifications[$j], "[$j]isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td>
                                        <?= Html::a(' ', 
                                                    ['delete-certificate', 'certificate_id' => $csecqualifications[$j]->csecqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table><br/>
                </fieldset> 
                
                <div style="margin-left:2.5%;" class="form-group">
                    <a class="btn btn-success glyphicon glyphicon-user" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'applicantusername' => $username]);?> role="button">  Modify Applicant Details</a>
           
                    <a class='btn btn-success glyphicon glyphicon-plus' href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-qualification-from-verify', 'applicantusername' => $username, 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' =>$type ]);?> role='button'> Add Certificate</a>
                    
                    <?php if (Yii::$app->user->can('verifyApplicants') && $dataProvider->getModels()): ?>
                        <?= Html::submitButton('Update Certificates', ['class' => 'btn btn-primary']) ?>
                    <?php endif; ?>
                    
                    <?php if (PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == false):?>
                        <?= Html::submitButton('Save All As Verified', ['class' => 'btn btn-primary', 'name'=>'verified']) ?>
                    <?php endif; ?>
                    
                    <!--<?php if (Yii::$app->user->can('addCertificate')): ?>
                        <?= Html::submitButton('Add Related Certificates', ['class' => 'btn btn-primary', 'name'=>'add_more']) ?>
                        <?= Html::dropDownList('add_more_value', 1, 
                                array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8', 9=>'9', 10=>'10')) ?>
                    <?php endif; ?>-->
                </div> 
                
                
                <?php
                    Modal::begin([
                        'header' => '<h2>Notification</h2>',
                        'id' => 'modal-no-more-qualifications',
                        'size' => 'modal-md',
                    ]);
                    echo "<p><strong>No records are present to be removed. If you would have previously deleted records, click "
                    . "save to finalize the operation</strong>.</p>";
                    Modal::end();
                ?>
            
                <?php
                    Modal::begin([
                        'header' => '<h2>Notification</h2>',
                        'id' => 'modal-too-many-qualifications',
                        'size' => 'modal-md',
                    ]);
                    echo "<p><strong>You have reached your record limit. No more records can be entered.</strong>.</p>";
                    Modal::end();
                ?>
                
                    
                <?php if(PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true) :?>
                    <br/><fieldset style="margin-left:2.5%; width:95%">
                        <legend><strong>Post Secondary Degree</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                            <thead>
                                <tr>
                                    <th>Name of Degree</th>
                                    <th>Awarding Institution</th>
                                    <th>Year Awarded</th>
                                    <th>Verified</th>
                                    <th>Queried</th>
                                    <th>Delete</th>
                                </tr>
                            <thead>

                            <tbody>    
                                <tr>
                                    <td width=30% style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'name')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'awardinginstitution')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'yearawarded')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($post_qualification, "isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($post_qualification, "isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td style="vertical-align:middle">
                                        <?= Html::a(' ', 
                                                    ['delete-post-secondary-qualification', 'recordid' => $post_qualification->postsecondaryqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                     'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br/>
                    </fieldset>
                <?php else:?>
                     <br/><fieldset style="margin-left:2.5%; width:95%">
                        <legend><strong>Post Secondary Degree</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-hover table-striped" style="width:100%; margin: 0 auto;">
                            <tv>
                                <td>Applicant has not indicated that they have a post secondary degree</td>
                            </tr>
                                              
                            <tr>
                                <?php
                                    $add_role = Url::toRoute(['/subcomponents/admissions/verify-applicants/add-post-secondary-qualification', 'personid' => $applicant->personid, 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' => $type]);
                                ?>
                                <td><a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=$add_role?> role="button"> Add Post Secondary Qualification</a></td>
                             </tr>
                        </table> 
                <?php endif;?>

                     
                <div style="margin-left:2.5%;" class="form-group">
                    <?php if (Yii::$app->user->can('verifyApplicants') && $dataProvider->getModels()  && PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true): ?>
                        <br/><?= Html::submitButton('Update Degree', ['class' => 'btn btn-primary']) ?>
                        <?= Html::submitButton('Save All As Verified', ['class' => 'btn btn-primary', 'name'=>'verified']) ?>
                    <?php endif; ?>
                </div>

            <?php ActiveForm::end(); ?>
            
                       
            
        </div>
    </div>
</div>
    
    
    
    
    
    