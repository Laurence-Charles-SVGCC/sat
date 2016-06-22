<?php

/* 
 * Author: Laurence Charles
 * Date Created: 27/04/2016
 */
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
  
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;

?>

    <div class="programme_result">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'format' => 'html',
                        'value' => function($row)
                            {
                                return Html::a($row['name'], 
                                                Url::to(['programmes/programme-overview', 'programmecatalogid' => $row['programmecatalogid']]));
                            }
                    ],
                    [
                        'attribute' => 'qualificationtype',
                        'format' => 'text',
                        'label' => 'Qualification'
                    ],
                    [
                        'attribute' => 'specialisation',
                        'format' => 'text',
                        'label' => 'Specialisation'
                    ],
                    [
                        'attribute' => 'department',
                        'format' => 'text',
                        'label' => 'Department'
                    ],
                    [
                        'attribute' => 'exambody',
                        'format' => 'text',
                        'label' => 'Exam Body'
                    ],
                    [
                        'attribute' => 'programmetype',
                        'format' => 'text',
                        'label' => 'Type'
                    ],     
                    [
                        'attribute' => 'duration',
                        'format' => 'text',
                        'label' => 'Duration'
                    ],
                ],
            ]); 
        ?>     
    </div>
