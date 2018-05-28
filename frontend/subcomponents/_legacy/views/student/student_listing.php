<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    use frontend\models\LegacyMarksheet;
?>

<div class="legacy_student_listing">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'title',
                'format' => 'text',
                'label' => 'Title'
            ],
            [
                'format' => 'html',
                'label' => 'Full Name',
                'value' => function($row)
                {
                    return Html::a($row['firstname'], 
                                            Url::to(['student/view', 
                                                      'id' => $row['studentid']
                                                  ])
                                            );
                }
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
                'attribute' => 'dateofbirth',
                'format' => 'text',
                'label' => 'Date of Birth'
            ],
            [
                'attribute' => 'gender',
                'format' => 'text',
                'label' => 'Gender'
            ],
             [
                'attribute' => 'address',
                'format' => 'text',
                'label' => 'Address'
            ],
            [
                'attribute' => 'admissionyear',
                'format' => 'text',
                        'label' => 'Year of Admission'
            ],
            [
                'attribute' => 'faculty',
                'format' => 'text',
                'label' => 'Faculty'
            ],
            [
                'format' => 'html',
                'label' => 'Delete',
                'value' => function($row)
                {
                    if (LegacyMarksheet::find()->where(['legacystudentid' => $row['studentid'], 'isactive' => 1, 'isdeleted' => 0])->one() == true)
                    {
                        return "N/A";
                    }
                    else
                    {
                        return Html::a(' ', 
                                                Url::toRoute(['/subcomponents/legacys/subjects/delete-student', 'id' => $row['studentid']]),
                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this record?',
                                                        'method' => 'post',
                                                    ]
                                                ]);
                    }
                }
            ],
        ],
    ]); ?>     
</div>

