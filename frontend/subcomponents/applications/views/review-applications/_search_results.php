<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\web\UrlManager;
?>

<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'email',
                'format' => 'text',
                'label' => 'Email'
            ],
            [
                'attribute' => 'applicantname',
                'format' => 'text',
                'label' => 'ApplicantID'
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
                'attribute' => 'status',
                'format' => 'text',
                'label' => 'Status'
            ],
            [
                'attribute' => 'username',
                'format' => 'text',
                'label' => 'Username'
            ],
            [
                'format' => 'html',
                'label' => 'Email Verification',
                'value' => function($row)
                {
                    if (true)
                    {
                         return Html::a(' Send', ['resend-verification-email', 'id' => $row['applicantregistrationid']], ['class' => 'btn btn-success glyphicon glyphicon-send' ]);
                    }
                    else
                    {
                        return "--";
                    }
                  
                }
            ],
            [
                'format' => 'html',
                'label' => 'Submission Email',
                'value' => function($row)
                {
                    if (true)
                    {
//                         return Html::a(' Send', ['resend-submission-completion-email', 'id' => $row['applicantregistrationid']], ['class' => 'btn btn-success glyphicon glyphicon-send' ]);
                         return "N/A";
                    }
                    else
                    {
                        return "--";
                    }
                  
                }
            ],
        ],
    ]); ?>     
</div>