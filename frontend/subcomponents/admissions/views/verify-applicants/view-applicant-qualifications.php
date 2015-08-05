<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
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
                          <td>
                              <?= $form->field($model, "[$key]examinationbodyid", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'name'))?>
                          </td>
                          <td> 
                              <?= $form->field($model, "[$key]subjectid", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(Subject::find()->all(), 'subjectid', 'name')) ?>
                          </td>
                          <td>
                              <?= $form->field($model, "[$key]examinationproficiencytypeid", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(ExaminationProficiencyType::find()->all(), 'examinationproficiencytypeid', 'name')) ?>
                          </td>
                        <td> 
                            <?= $form->field($model, "[$key]examinationgradeid", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->textInput(); ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$key]year", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->textInput(); ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$key]isverified", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->checkbox(['label' => NULL]); ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$key]isqueried", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->checkbox(['label' => NULL]); ?>
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
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::submitButton('Save As Verified', ['class' => 'btn btn-primary', 'name'=>'verified']) ?>
                <?= Html::submitButton('Add Subjects', ['class' => 'btn btn-primary', 'name'=>'add_more']) ?>
                <?= Html::dropDownList('add_more_value', 1, 
                        array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8', 9=>'9', 10=>'10')) ?>
            </div>
        <?php ActiveForm::end(); ?>

    </div>
    
    
    
    
    
    