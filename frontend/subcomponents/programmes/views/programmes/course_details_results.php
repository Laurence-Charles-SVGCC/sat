<?php

/* 
 * Author: Laurence Charles
 * Date Created: 13/06/2016
 */
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

?>

    <div class="course_details_result">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Course Code',
                        'format' => 'html',
                        'value' => function($row)
                            {
                                return Html::a($row['coursecode'], 
                                                Url::to(['programmes/course-management', 'iscape' => 0,  'programmecatalogid' => $row['programmecatalogid'], 'academicofferingid' => $row['academicofferingid'],  'code' => $row['coursecodeid']])); 
                            }
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Course Name'
                    ],
                    [
                        'attribute' => 'lecturer',
                        'format' => 'text',
                        'label' => 'Lecturer'
                    ],
                    [
                        'attribute' => 'semester-title',
                        'format' => 'text',
                        'label' => 'Semester'
                    ],
                ],
            ]); 
        ?>     
    </div>


