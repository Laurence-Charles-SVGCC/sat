<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="verify-applicants-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Applicant ID',
                'value' => function($row)
                    {
                        return $row['studentno'];
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