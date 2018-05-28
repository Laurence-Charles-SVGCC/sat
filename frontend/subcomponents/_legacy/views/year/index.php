<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
     use yii\grid\GridView;
    
     use frontend\models\LegacyTerm;
     
     $this->title = 'Legacy Year Listing';
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/year/index']);?>" title="Legacy Years">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php if (true/*Yii::$app->user->can('createLegacyYear')*/): ?>
        <div class="box-header with-border">
            <?= Html::a(' Create Year', ['year/create'], ['class' => 'btn btn-info pull-right']) ?>
        </div>
    <?php endif; ?>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Name'
                    ],
                    [
                        'attribute' => 'createdby',
                        'format' => 'text',
                        'label' => 'Created By'
                    ],
                    [
                        'attribute' => 'datecreated',
                        'format' => 'text',
                        'label' => 'Date Created'
                    ],
                    [
                        'attribute' => 'lastmodifiedby',
                        'format' => 'text',
                        'label' => 'Last Modified By'
                    ],
                    [
                        'attribute' => 'datemodified',
                        'format' => 'text',
                        'label' => 'Date Modified'
                    ],
                    [
                        'format' => 'html',
                        'label' => 'Delete',
                        'value' => function($row)
                        {
                            if (LegacyTerm::find()->where(['legacyyearid' => $row['yearid'], 'isactive' => 1, 'isdeleted' => 0])->one() == true)
                            {
                                return "N/A";
                            }
                            else
                            {
                                return Html::a(' ', 
                                                        Url::toRoute(['/subcomponents/legacys/subjects/delete-year', 'id' => $row['yearid']]),
                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                            'data' => [
                                                                'confirm' => 'Are you sure you want to delete this record?',
                                                                'method' => 'post',
                                                            ]
                                                        ]);
                            }
                        }
                    ],
                ],
            ]); 
       ?>
    </div>
</div>