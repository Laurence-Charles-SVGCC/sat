<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

?>

<div class="successful-applicants">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'options' => ['style' => 'width: 95%; margin: 0 auto;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Applicant ID',
                'value' => function($row)
                    {
                       return Html::a($row['username'], 
                                        Url::to(['register-student/view-prospective-student',
                                                 'personid' => $row['personid'],
                                                 'programme' => $row['programme_name'],
                                                ])
                                    );

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
                'attribute' => 'programme_name',
                'format' => 'text',
                'label' => 'Programme'
            ],
        ],
    ]); ?>
    
</div>

