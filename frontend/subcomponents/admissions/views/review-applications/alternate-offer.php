<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;

    $this->title = 'Make Alternate Offer';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="alternate-offer">
    <div class = "custom_wrapper">
        
        <div class="custom_body">
            <h1><?= Html::encode($this->title) ?></h1>
            <h2><?= $firstname . " " . $middlename . " " . $lastname . "(" . $applicantid . ")" ?></h2>
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
                        'label' => 'Action',
                        'value' => function($row) use ($division_id, $application_status)
                        {
                           return $row['offerable'] ? Html::a('Offer', Url::to(['review-applications/make-offer',
                                   'applicationid' => $row['applicationid'], 'division_id' => $division_id, 'application_status' => $application_status]), 
                                    ['class' => 'btn btn-success']) 
                                   : '';
                        }
                    ], 
                ],
            ]); ?>
            <?php if (Yii::$app->user->can('createOffer')): ?>
                <h3>Alternate Offer Details</h3>
                <?php ActiveForm::begin(
                        [
                            'action' => Url::to(['review-applications/alternate-offer']),
                        ]
                        ); ?>
                        <?= Html::hiddenInput('division_id', $division_id); ?>
                        <?= Html::hiddenInput('applicantid', $applicantid); ?>
                        <?= Html::hiddenInput('application_status', $application_status); ?>
                        <div class="row">
                            <div class="col-lg-2">
                                <?= Html::label( 'Choose Programme',  'programme'); ?>
                                <?= Html::dropDownList('programme', null, 
                                    ArrayHelper::map($programmes, 'programmecatalogid', 'name' )); ?>
                            </div>
                        </div>
                        <div class="row">
                            <h4>CAPE Groups</h4>
                                <?php foreach($cape_data as $grp_name => $cd): ?>
                                    <div class="col-md-3">
                                        <strong><?= $grp_name ?></strong>
                                    <?php foreach($cd as $subject): ?>                           
                                        <br/><?= Html::checkbox("cape_subject[" . $subject->getCapesubject()->one()->capesubjectid . "]"); ?>
                                        <?= $subject->getCapesubject()->one()->subjectname; ?>                        
                                     <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                        </div>
                        <?= Html::submitButton("Submit", ['class' => 'btn btn-success']); ?>
                <?php ActiveForm::end() ?>
            <?php endif; ?>
        </div>
    </div>
</div>