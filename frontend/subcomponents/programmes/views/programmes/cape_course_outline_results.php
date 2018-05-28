<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>

    <div class="cape_course_outline_result">
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
                                if($row['has_outline'])
                                {
                                    return Html::a($row['coursecode'], 
                                                    Url::to(['programmes/course-description', 'iscape' => 1,  'programmecatalogid' => $row['programmecatalogid'], 'coursecatalogid' => $row['capecourseid']]));
                                }
                                else
                                {
                                    return $row['coursecode'];
                                }
                            }
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Course Name'
                    ],
                    [
                        'attribute' => 'subject',
                        'format' => 'text',
                        'label' => 'Subject Name'
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


