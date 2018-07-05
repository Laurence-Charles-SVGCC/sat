<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\grid\GridView;
    
     use frontend\subcomponents\legacy\models\LegacyLevel;
     
     $this->title = $title;
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding">
    <div class="box-header with-border">
        <h2 class="text-center">
            <span><?= $title?></span>
            <?php if (true/*Yii::$app->user->can('editLegacyLevel') == true*/): ?>
                <?= Html::a(' Create Level', ['level/create'], ['class' => 'btn btn-info pull-right']) ?>
            <?php endif; ?>
        </h2>
    </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Name'
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'text',
                        'label' => 'Status'
                    ],
                    [
                        'format' => 'html',
                        'label' => 'Delete',
                        'value' => function($row)
                        {
                            return LegacyLevel::genrateUIAction($row['legacylevelid']);

//                                return Html::a(' ', 
//                                                        Url::toRoute(['/subcomponents/legacys/subjects/delete-year', 'id' => $row['yearid']]),
//                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
//                                                            'data' => [
//                                                                'confirm' => 'Are you sure you want to delete this record?',
//                                                                'method' => 'post',
//                                                            ]
//                                                        ]);
//                            }
                        }
                    ],
                ],
            ]); 
       ?>
    </div>
</div>