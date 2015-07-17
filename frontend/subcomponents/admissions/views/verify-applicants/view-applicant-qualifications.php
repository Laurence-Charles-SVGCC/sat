<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
//use yii\helpers\Url;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Applicant: ';
/*$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];*/
$this->params['breadcrumbs'][] = $this->title;


?>
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
                  </tr>
                </thead>
                    
                    <tbody>
                    <?php foreach ($dataProvider->getModels() as $key=>$model): ?>
                      <tr>
                          <td>
                              <?= $form->field($model, "examinationbodyid", ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'name'))?>
                          </td>
                          <td> 
                              <?= $form->field($model, 'subjectid', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(Subject::find()->all(), 'subjectid', 'name')) ?>
                          </td>
                          <td>
                              <?= $form->field($model, 'examinationproficiencytypeid', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->dropDownList(
                                           ArrayHelper::map(ExaminationProficiencyType::find()->all(), 'examinationproficiencytypeid', 'name')) ?>
                          </td>
                        <td> 
                            <?= $form->field($model, 'grade', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->textInput(); ?>
                        </td>
                        <td>
                            <?= $form->field($model, 'year', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->textInput(); ?>
                        </td>
                        <td>
                            <?= $form->field($model, 'isverified', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->checkbox(['label' => NULL]); ?>
                        </td>
                        <td>
                            <?= $form->field($model, 'isqueried', ['options' => [
                                        'tag'=>'div',
                                        'class' => 'form-group field-loginform-username has-feedback required',
                                        ],
                                        'template' => '{input}{error}'
                                    ])->checkbox(['label' => NULL]); ?>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
                <button onclick="addRow()">Try it</button>

        <?php ActiveForm::end(); ?>

    </div>
    <script>
        function addRow() {
            //alert('cell1 is ' +  document.getElementsByClassName("field-csecqualification-examinationbodyid-0")[0].innerHTML);
            var table = document.getElementById("certificate_table");
            var row = table.insertRow(2);
            
            //var cell1 = row.insertCell(0);
            //cell1.innerHTML =  "test";//document.getElementsByClassName("field-csecqualification-examinationbodyid-0")[0].innerHTML;
        }
</script>
    
    
    
    
    
    