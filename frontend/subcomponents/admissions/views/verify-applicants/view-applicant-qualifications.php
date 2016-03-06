<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use frontend\models\CsecCentre;
use frontend\models\PostSecondaryQualification;

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
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php $form = ActiveForm::begin(); ?>
                <br/><fieldset style="margin-left:2.5%; width:95%">
                    <legend><strong>Certificate Results</strong></legend>
                    <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                        <thead>
                          <tr>
                            <?php if($isexternal == 1):?>
                                <th>Centre Name</th>
                            <?php endif;?>
                            <th>Examining Body</th>
                            <th>Subject</th>
                            <th>Proficiency</th>
                            <th>Grade</th>
                            <th>Year</th>
                            <th>Verified</th>
                            <th>Queried</th>
                            <th>Action</th>
                          </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($dataProvider->getModels() as $key=>$model): ?>
                                <tr>
                                    <?= Html::activeHiddenInput($model, "[$key]csecqualificationid"); ?>
                                    <?php if($isexternal == 1):?>
                                        <td width = 15%>
                                            <?=  $form->field($model, "[$key]cseccentreid", ['options' => [
                                                    'tag'=>'div',
                                                    ],
                                                    'template' => '{input}{error}'
                                                ])->dropDownList(
                                                        ArrayHelper::map(CsecCentre::find()->all(), 'cseccentreid', 'name'))?>
                                        </td>
                                    <?php endif;?>

                                    <td width = 15%>
                                        <?=  $form->field($model, "[$key]examinationbodyid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'abbreviation'))?>
                                    </td>

                                    <td width = 20%> 
                                        <?= $form->field($model, "[$key]subjectid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(Subject::find()->where(['examinationbodyid' => $model->examinationbodyid])
                                                           ->all(), 'subjectid', 'name')) ?>
                                    </td>

                                    <td width = 15%>
                                        <?= $form->field($model, "[$key]examinationproficiencytypeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationProficiencyType::find()->where(['examinationbodyid' => $model->examinationbodyid])
                                                           ->all(), 'examinationproficiencytypeid', 'name')) ?>
                                    </td>

                                    <td width = 15%> 
                                        <?= $form->field($model, "[$key]examinationgradeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationGrade::find()->where(['examinationbodyid' => $model->examinationbodyid])
                                                           ->all(), 'examinationgradeid', 'name')); ?>
                                    </td>

                                    <td width = 10%>
                                        <?= $form->field($model, "[$key]year", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->textInput(); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($model, "[$key]isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($model, "[$key]isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td>
                                        <?= Html::a(' Delete', 
                                                    ['delete-certificate', 'certificate_id' => $model->csecqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table><br/>
                </fieldset> 
                
                <div style="margin-left:2.5%;" class="form-group">
                    <?php if (Yii::$app->user->can('verifyApplicants') && $dataProvider->getModels()): ?>
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Certificates', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?php endif; ?>
                    
                    <?php if (PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == false):?>
                        <?= Html::submitButton('Save All As Verified', ['class' => 'btn btn-primary', 'name'=>'verified']) ?>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->user->can('addCertificate')): ?>
                        <?= Html::submitButton('Add Certificates', ['class' => 'btn btn-primary', 'name'=>'add_more']) ?>
                        <?= Html::dropDownList('add_more_value', 1, 
                                array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8', 9=>'9', 10=>'10')) ?>
                    <?php endif; ?>
                </div>  
                    
                <?php if(PostSecondaryQualification::getPostSecondaryQualifications($model->personid) == true) :?>
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
                                    <th>Action</th>
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
                                        <?= Html::a(' Delete', 
                                                    ['post-secondary-qualification', 'recordid' => $post_qualification->postsecondaryqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br/>
                    </fieldset>
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
    
    
    
    
    
    