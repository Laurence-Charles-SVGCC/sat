<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
?>

<div class="cape_batch_students">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Student ID',
                        'format' => 'html',
                        'value' => function($row)
                            {
                                return Html::a($row['studentid'], 
                                                Url::toRoute(['/subcomponents/gradebook/gradebook/transcript', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                            }
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
                        'attribute' => 'coursecode',
                        'format' => 'text',
                        'label' => 'Course Code'
                    ],
                    [
                        'attribute' => 'coursename',
                        'format' => 'text',
                        'label' => 'Course Name'
                    ],
                    [
                        'attribute' => 'subject',
                        'format' => 'text',
                        'label' => 'Subject'
                    ],
                    [
                        'attribute' => 'semester',
                        'format' => 'text',
                        'label' => 'Sem.'
                    ],
                    [
                        'attribute' => 'coursework',
                        'format' => 'text',
                        'label' => 'Cousework'
                    ],
                    [
                        'attribute' => 'exam',
                        'format' => 'text',
                        'label' => 'Exam'
                    ],
                    [
                        'attribute' => 'final',
                        'format' => 'text',
                        'label' => 'Final'
                    ],
                ]
            ]); 
        ?>     
    </div>