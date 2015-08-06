<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Applicant View';
$this->params['breadcrumbs'][] = ['label' => 'Applicant View', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-period-form">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Details for: 
        <?= $applicant->title . " " . $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname 
        . " (" . $username . ")" ?>
    </h2>
    <h3>Applicant's Choices</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'order',
                'format' => 'text',
                'label' => 'Choice Order'
            ],
            [
                'attribute' => 'applicationid',
                'format' => 'text',
                'label' => 'Application ID',
            ],
            [
                'attribute' => 'programme_name',
                'format' => 'text',
                'label' => 'Programme',
            ],
            [
                'format' => 'text',
                'label' => 'Subjects',
                'value' => function($row)
                {
                   return $row['subjects'] == '' ? 'N/A': $row['subjects'];
                }
            ],
            [
                'format' => 'html',
                'label' => 'Offer ID',
                'value' => function($row)use ($applicant, $username)
                    {
                        if ($row['offerid'])
                        {
                            return Html::a($row['offerid'], 
                               Url::to(['offer/view', 'id' => $row['offerid']]));
                        }
                        else
                        {
                            if (Yii::$app->user->can('reviewApplications'))
                            {
                               return Html::a('Review', 
                                            Url::to(['review-applications/view-applicant-certificates', 'applicationid' => $row['applicationid'],
                                                'firstname'=>$applicant->firstname, 'middlename' => $applicant->middlename, 'lastname'=>$applicant->lastname,
                                                'programme'=>$row['programme_name'], 'applicantid'=>$username, 'application_status'=>'pending']),
                                                    ['class' => 'btn btn-success']);
                            }
                            return 'N/A';
                        }
                    }
            ],
        ],
    ]); ?>
    <?php ActiveForm::begin(
    [
        'action' => Url::to(['view-applicant/applicant-actions'])
    ]); ?>
        <?= Html::hiddenInput('applicantusername', $username); ?>
        <?php if (Yii::$app->user->can('registerStudent')): ?>
            <?= Html::submitButton('Register as Student', ['class' => 'btn btn-success', 'name' => 'register']); ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('viewApplicantPersonal')): ?>
            <?= Html::submitButton('View Personal Details', ['class' => 'btn btn-success', 'name' => 'view_personal']); ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('editApplicantPersonal')): ?>
            <?= Html::submitButton('Edit Personal Details', ['class' => 'btn btn-success', 'name' => 'edit_personal']); ?>
        <?php endif; ?>
    <?php ActiveForm::end(); ?>
    
</div>