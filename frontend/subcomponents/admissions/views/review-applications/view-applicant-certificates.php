<?php

use yii\widgets\ActiveForm;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use frontend\models\ExaminationBody;
use frontend\models\ExaminationProficiencyType;
use frontend\models\Subject;
use frontend\models\ExaminationGrade;
use frontend\models\ApplicationStatus;

$this->title = 'Applicant Certificates';
//$this->params['breadcrumbs'][] = ['label' => 'Manage Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-applicants-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= $firstname . " " . $middlename . " " . $lastname . "(" . $applicantid . ")" ?></h2>
    <h2><?= "Applied to: " . $programme ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'text',
                'label' => 'Examination Body',
                'value' => function($model)
                    {
                        $exam_body = ExaminationBody::find()->where(['examinationbodyid' => $model->examinationbodyid])->one();
                       return $exam_body ? $exam_body->name : "Undefined";
                    }
            ],
            [
                'attribute' => 'year',
                'format' => 'text',
                'label' => 'Year'
            ],
            [
                'attribute' => 'proficiency',
                'format' => 'text',
                'label' => 'Proficiency',
                'value' => function($model)
                    {
                        $exam_proficiency = ExaminationProficiencyType::find()->where(['examinationproficiencytypeid' => 
                            $model->examinationproficiencytypeid])->one();
                       return $exam_proficiency ? $exam_proficiency->name : "Undefined";
                    }
            ],
            [
                'attribute' => 'subjectid',
                'format' => 'text',
                'label' => 'Subject',
                'value' => function($model)
                    {
                        $subject = Subject::find()->where(['subjectid' => $model->subjectid])->one();
                       return $subject ? $subject->name : "Undefined";
                    }
            ],
            [
                'attribute' => 'examinationgradeid',
                'format' => 'text',
                'label' => 'Grade',
                'value' => function($model)
                    {
                        $exam_grade = ExaminationGrade::find()->where(['examinationgradeid' => $model->examinationgradeid])->one();
                       return $exam_grade ? $exam_grade->name : "Undefined";
                    }
            ],
        ],
    ]); ?>
    
    <p>
        <?php ActiveForm::begin(
                [
                    'action' => Url::to(['review-applications/process-application']),
                ]
          ); ?>
        <?= Html::hiddenInput('applicationid', $applicationid); ?>
        <?= Html::hiddenInput('application_status', $application_status); ?>
        <?= Html::hiddenInput('firstname', $firstname); ?>
        <?= Html::hiddenInput('middlename', $middlename); ?>
        <?= Html::hiddenInput('lastname', $lastname); ?>
        <?= Html::hiddenInput('programme', $programme); ?>
        <?= Html::hiddenInput('applicantid', $applicantid); ?>
        <?php $app_status = ApplicationStatus::find()->where(['applicationstatusid' => $application_status])->one();
                $status_name = $app_status ? $app_status->name : ''; ?>
        <?php //Implement change in way the offer button shows, i.e interview offer if programme is such ?>
        <?php if (Yii::$app->user->can('createOffer')): ?>
            <?php if (strcasecmp($status_name, "offer")): ?>
                <?= Html::submitButton('Make Offer', ['class' => 'btn btn-success', 'name'=>'make_offer']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('giveInterviewOffer')): ?>
            <?php if (strcasecmp($status_name, "interviewOffer")): ?>
                <?= Html::submitButton('Interview', ['class' => 'btn btn-success', 'name'=>'interview']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('reviewApplications')): ?>
            <?php if (strcasecmp($status_name, "shortlist")): ?>
                <?= Html::submitButton('Shortlist', ['class' => 'btn btn-primary', 'name'=>'shortlist']) ?>
            <?php endif; ?>
            <?php if (strcasecmp($status_name, "borderline")): ?>
                <?= Html::submitButton('Borderline', ['class' => 'btn btn-primary', 'name'=>'borderline']) ?>
            <?php endif; ?>
            <?php if (strcasecmp($status_name, "rejected")): ?>
                <?= Html::submitButton('Reject', ['class' => 'btn btn-primary', 'name'=>'reject']) ?>
            <?php endif; ?>
            <?php if (strcasecmp($status_name, "referred")): ?>
                <?= Html::submitButton('Refer', ['class' => 'btn btn-primary', 'name'=>'refer']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('createOffer')): ?>
            <?= Html::submitButton('Alternate Offer', ['class' => 'btn btn-primary', 'name'=>'alternate_offer']) ?>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
    </p>

</div>