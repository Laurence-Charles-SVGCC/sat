<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use yii\helpers\ArrayHelper;

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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <div class="view-applicant-qualifications">
        <?php $form = ActiveForm::begin(); ?>
              <table id="certificate_table" class="table table-bordered table-striped">
                <thead>
                  <tr>
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
                        <td width= 5%>
                            <?= $form->field($model, "[$key]isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                        </td>
                        <td width= 5%>
                            <?= $form->field($model, "[$key]isqueried")->checkbox(['label' => NULL]); ?>
                        </td>
                        <td>
                            <a class="btn" href="<?= Url::to(['delete-certificate',
                                'certificate_id' => $model->csecqualificationid])?>">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
           
            <div class="form-group">
                <?php if (Yii::$app->user->can('verifyApplicants') && $dataProvider->getModels()): ?>
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?= Html::submitButton('Save As Verified', ['class' => 'btn btn-primary', 'name'=>'verified']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('addCertificate')): ?>
                    <?= Html::submitButton('Add Subjects', ['class' => 'btn btn-primary', 'name'=>'add_more']) ?>
                    <?= Html::dropDownList('add_more_value', 1, 
                            array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8', 9=>'9', 10=>'10')) ?>
                <?php endif; ?>
            </div>
          
        <?php ActiveForm::end(); ?>

    </div>
    
    
    
    
    
    