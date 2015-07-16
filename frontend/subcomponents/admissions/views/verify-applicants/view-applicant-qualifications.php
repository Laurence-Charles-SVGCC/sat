<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
//use yii\helpers\Url;

use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Applicant: ';
/*$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];*/
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($dataProvider->getModels() as $model): ?>
              <tr>
                  <td>
                      <?= $form->field($model, 'examinationbodyid')->textInput() ?>
                  </td>
                  <td>
                     <?php $subject = Subject::findOne($model->subjectid); ?>
                       <?= $subject ? $subject->name : 'Undefined subject ID: ' . $model->subjectid; ?>
                  </td>
                <td>Win 95+</td>
                <td> 4</td>
                <td>X</td>
              </tr>
              <?php endforeach; ?>
        </tbody>
      </table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    
    <!--<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'examinationbodyid',
                'format' => 'text',
                'label' => 'Examining Body',
                'value' => function($model)
                    {
                       $body = ExaminationBody::findOne($model->examinationbodyid);
                       return $body ? $body->name : 'Undefined Examination Body ID: ' . $model->examinationbodyid;
                    }
            ],
            [
                'attribute' => 'subjectid',
                'format' => 'text',
                'label' => 'Subject',
                'value' => function($model)
                    {
                       $body = Subject::findOne($model->subjectid);
                       return $body ? $body->name : 'Undefined subject ID: ' . $model->subjectid;
                    }
            ],
            [
                'attribute' => 'examinationproficiencytypeid',
                'format' => 'text',
                'label' => 'Proficiency',
                'value' => function($model)
                    {
                       $body = ExaminationProficiencyType::findOne($model->examinationproficiencytypeid);
                       return $body ? $body->name : 'Undefined proficiency ID: ' . $model->examinationproficiencytypeid;
                    }
            ],
            [
                'attribute' => 'grade',
                'format' => 'text',
                'label' => 'Grade'
            ],
            [
                'attribute' => 'year',
                'format' => 'text',
                'label' => 'Year'
            ],
            [
                'attribute' => 'isverified',
                'format' => 'boolean',
                'label' => 'Verified',
            ],
            [
                'attribute' => 'isqueried',
                'format' => 'boolean',
                'label' => 'Queried',
            ],
            ['class' => 'yii\grid\ActionColumn'],
            /*[
                'attribute' => 'gender',
                'format' => 'text',
                'label' => 'Gender'
            ],  */        
        ],
    ]); ?>-->

</div>