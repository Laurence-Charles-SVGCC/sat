<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

?>

<div class="pending-applicants">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'options' => ['style' => 'width: 95%; margin: 0 auto;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Applicant ID',
                'value' => function($row,$status)
                    {
                        if ($row['has_deprecated_application'] || $row['has_offer'])
                        {
                            if($status == "pending-unlimited") //no restriction to application period being incomplete
                            {
                                return Html::a($row['username'], 
                                                 Url::to(['view-applicant/applicant-profile',
                                                          'applicantusername' => $row['username'],
                                                          'unrestricted' => true
                                                         ])
                                             );
                            }
                            else
                            {
                                return Html::a($row['username'], 
                                             Url::to(['view-applicant/applicant-profile',
                                                      'applicantusername' => $row['username']
                                                     ])
                                         );
                            }
                        }
                        else
                        {
                           return Html::a($row['username'], 
                                        Url::to(['process-applications/view-applicant-certificates',
                                                 'personid' => $row['personid'],
                                                 'programme' => $row['programme_name'], 
                                                 'application_status' => $row['application_status']
                                                ])
                                    );
                        }
                    }
            ],
            [
                'attribute' => 'firstname',
                'format' => 'text',
                'label' => 'First Name'
            ],
            [
                'attribute' => 'middlename',
                'format' => 'text',
                'label' => 'Middle Name(s)'
            ],
            [
                'attribute' => 'lastname',
                'format' => 'text',
                'label' => 'Last Name'
            ],
            [
                'attribute' => 'gender',
                'format' => 'text',
                'label' => 'Gender'
            ],
            [
                'attribute' => 'dateofbirth',
                'format' => 'text',
                'label' => 'Date of Birth'
            ],
        ],
    ]); ?>
    
</div>
