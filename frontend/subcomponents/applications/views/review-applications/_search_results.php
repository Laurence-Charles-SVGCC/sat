<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\web\UrlManager;
    use frontend\models\ApplicantRegistration;
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
                'attribute' => 'start_date',
                'format' => 'text',
                'label' => 'Start Date'
            ],
            [
                'attribute' => 'submission_date',
                'format' => 'text',
                'label' => 'Submission Date'
            ],
            [
                'format' => 'html',
                'label' => 'Email Verification',
                'value' => function($row)
                {
                    if (true)
                    {
                         return Html::a(' Resend', ['resend-verification-email', 'id' => $row['applicantregistrationid']], ['class' => 'btn btn-default glyphicon glyphicon-send']);
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
                    $registrant = ApplicantRegistration::find()
                            ->where(['applicantname' => $row['applicantname']])
                            ->one();
                    if ($registrant->getStatus() == "Submitted")
                    {
                        return Html::a(' Resend', ['resend-submission-confirmation-email', 'id' => $row['applicantregistrationid']], ['class' => 'btn btn-primary glyphicon glyphicon-send' ]);
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