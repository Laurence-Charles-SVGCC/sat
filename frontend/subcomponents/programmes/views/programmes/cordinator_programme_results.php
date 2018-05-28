<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    
    use frontend\models\Division;
    use frontend\models\AcademicYear;
    use frontend\models\Semester;
    use frontend\models\Department;
    use frontend\models\ProgrammeCatalog;
    
    $this->title = 'Cordinator  Control Panel';
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php if ($is_programme_cordinator):?>
        <div class="box-header with-border">
            <span class="box-title"> Programme(s) Cordinated </span>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'label' => 'Name',
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
                            'attribute' => 'programmetype',
                            'format' => 'text',
                            'label' => 'Type'
                        ],   
                    ],
                ]); 
            ?>   
        </div>
     <?php endif;?>
</div>