<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
   
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
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
        ],
    ]); ?>     
</div>

