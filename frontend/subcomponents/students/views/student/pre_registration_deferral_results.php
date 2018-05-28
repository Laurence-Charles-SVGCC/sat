<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>


<div id="pre_registration_deferrals_listing">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'html',
                'label' => 'StudentID',
                'value' => function($row)
                {
                    return Html::a($row['username'], 
                                    Url::to(['view-applicant/applicant-profile', 'applicantusername' => $row['username'], 'unrestricted' => true ]));
                }
            ],
            [
                'attribute' => 'firstname',
                'format' => 'text',
                'label' => 'First Name'
            ],
            [
                'attribute' => 'lastname',
                'format' => 'text',
                'label' => 'Last Name'
            ], 
            [
                'attribute' => 'details',
                'format' => 'text',
                'label' => 'Details'
            ], 
            [
                'attribute' => 'deferraldate',
                'format' => 'text',
                'label' => 'Date Deferred'
            ],
            [
                'attribute' => 'deferredby',
                'format' => 'text',
                'label' => 'Deferral Officer'
            ], 
            [
                'attribute' => 'dateresumed',
                'format' => 'text',
                'label' => 'Date Enrolled'
            ], 
            [
                'attribute' => 'resumedby',
                'format' => 'text',
                'label' => 'Enrollment Officer'
            ], 
        ],
    ]); ?>     
</div>