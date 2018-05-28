<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>

<div class="find_a_student_result">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'html',
                'label' => 'Student ID',
                'value' => function($row)
                    {
                        return Html::a($row['studentno'], 
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
                'attribute' => 'current_programme',
                'format' => 'text',
                'label' => 'Programme'
            ],
            [
                'attribute' => 'studentstatus',
                'format' => 'text',
                'label' => 'Status'
            ],    
            [
                'attribute' => 'enrollments',
                'format' => 'text',
                'label' => 'Registrations'
            ],    
        ],
    ]); ?>     
</div>

