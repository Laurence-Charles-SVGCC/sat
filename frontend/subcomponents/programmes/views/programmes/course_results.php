<?php

/* 
 * Author: Laurence Charles
 * Date Created: 27/04/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
?>

    <div class="course_result">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'code',
                    'format' => 'text',
                    'label' => 'Course Code',
    //                'format' => 'html',
    //                'value' => function($row)
    //                    {
    //                        return Html::a($row['studentno'], 
    //                                        Url::to(['profile/student-profile', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
    //                    }
                ],
                [
                    'attribute' => 'name',
                    'format' => 'text',
                    'label' => 'Course Name'
                ],
                [
                    'attribute' => 'type',
                    'format' => 'text',
                    'label' => 'Course Type'
                ],
            ],
        ]); ?>     
    </div>