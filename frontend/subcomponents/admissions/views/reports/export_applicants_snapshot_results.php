<?php
    
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            'filename'=> $filename,
            'properties' =>[
            ],
//            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],
//                'username',
//                'firstname',
//                'lastname',
//                'programme',
//                'issuedby',
//                'issuedate',
//                'revokedby',
//                'ispublished',
//            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'username',
                    'format' => 'text',
                    'label' => 'Username'
                ],
                [
                    'attribute' => 'title',
                    'format' => 'text',
                    'label' => 'Title'
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
                    'attribute' => 'programme',
                    'format' => 'text',
                    'label' => 'Programme'
                ],
                [
                    'attribute' => 'email',
                    'format' => 'text',
                    'label' => 'Email'
                ],
                [
                    'attribute' => 'phone',
                    'format' => 'text',
                    'label' => 'Phone Number(s)'
                ],
            ],
        ]);