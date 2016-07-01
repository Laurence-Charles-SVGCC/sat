<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="verify-applicants-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Student ID',
                'value' => function($row)
                    {
                        return Html::a($row['studentno'], 
                                        Url::to(['student/view-student', 'studentid' => $row['studentid'], 'username' => $row['studentno']]));
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