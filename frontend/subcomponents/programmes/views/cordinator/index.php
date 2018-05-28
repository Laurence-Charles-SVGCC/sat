<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\grid\ActionColumn; 

    $this->title = 'Co-ordinator Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
         <?php if (Yii::$app->user->can('powerCordinator')): ?>
            <?= Html::a(' Assign Co-ordinator', ['create'], ['class' => 'btn btn-info pull-right']) ?>
        <?php endif;?>
    </div>
    
    <div class=""box-body>
        <table class="table table-hover">
            <?php if($dataProvider == false):?>
                <tr>
                    <td>No co-ordinators have been assigned</td>
                </tr>
            <?php else:?>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
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
                                'attribute' => 'cordinatortype',
                                'format' => 'text',
                                'label' => 'Cordinator Type'
                            ],
                            [
                                'attribute' => 'details',
                                'format' => 'text',
                                'label' => 'Details'
                            ],
                            [
                                'attribute' => 'academicyear',
                                'format' => 'text',
                                'label' => 'Academic Year'
                            ],
                            [
                                'attribute' => 'isserving',
                                'format' => 'boolean',
                                'label' => 'Serving'
                            ],
                            [
                                'format' => 'html',
                                'label' => 'Update',
                                'value' => function($row)
                                {
                                    if($row['isserving'] == 1)
                                    {
                                        return Html::a('Revoke', 
                                                                Url::to(['cordinator/update', 
                                                                          'action' => 'revoke', 
                                                                          'id' => $row['cordinatorid']
                                                                      ])
                                                                );
                                    }
                                    else
                                    {
                                        return Html::a('Re-assign', 
                                                                Url::to(['cordinator/update', 
                                                                            'action' => 'reassign', 
                                                                            'id' => $row['cordinatorid']
                                                                      ])
                                                                );
                                    }
                                }
                            ],
                            [
                                'format' => 'html',
                                'label' => 'Delete',
                                'value' => function($row)
                                {
                                    return Html::a(' ', 
                                                Url::to(['cordinator/delete-cordinator',
                                                            'id' =>$row['cordinatorid']
                                                        ]) ,
                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove']
                                            );
                                }      
                            ],
                        ],
                    ]); 
               ?>
            <?php endif;?>    
        </table>
    </div>
</div>