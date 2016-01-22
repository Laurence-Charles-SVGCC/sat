<?php

/* 
 * Renders the 'result' partial view for student search by id or name
 * 
 * Author: Laurence Charles
 * Date Created: 14/12/2015
 * Date Last Modified: 14/12/2015
 */
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>


<div class="find_a_student_result">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'attribute' => 'studentstatus',
                'format' => 'text',
                'label' => 'Status'
            ],     
        ],
    ]); ?>     
</div>

