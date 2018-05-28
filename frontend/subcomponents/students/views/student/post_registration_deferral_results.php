<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>


<div id="post_registration_deferrals_listing">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'html',
                'label' => 'StudentID',
                'value' => function($row)
                {
                    return Html::a($row['username'], 
                                    Url::to(['profile/student-profile', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
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
                'attribute' => 'previous_year_programme',
                'format' => 'text',
                'label' => 'Previous Programme'
            ],
            [
                'attribute' => 'current_year_programme',
                'format' => 'text',
                'label' => 'Current Programme'
            ], 
            [
                'attribute' => 'deferral_officer_name',
                'format' => 'text',
                'label' => 'Transfer Officer'
            ], 
            [
                'attribute' => 'date',
                'format' => 'text',
                'label' => 'Date'
            ],
        ],
    ]); ?>     
</div>