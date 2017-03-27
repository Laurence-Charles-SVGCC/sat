<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\LegacyBatch;

     $this->title = 'Subject Listing';
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/subjects/index']);?>" title="Legacy Subjects">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <h2 class="text-center"><?= $this->title?></h2>
     
    <?php if (true/*Yii::$app->user->can('createLegacySubject')*/): ?>
        <div class="box-header with-border">
            <?= Html::a('Create Subject', ['subjects/create'], ['class' => 'btn btn-info pull-right']) ?>
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
                            'label' => 'Subject Name'
                        ],
                        [
                            'attribute' => 'type',
                            'format' => 'text',
                            'label' => 'Subject Type'
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Delete',
                            'value' => function($row)
                            {
                                if (LegacyBatch::find()->where(['legacysubjectid' => $row['subjectid'], 'isactive' => 1, 'isdeleted' => 0])->one() == true)
                                {
                                    return "N/A";
                                }
                                else
                                {
                                    return Html::a(' ', 
                                                            Url::toRoute(['/subcomponents/legacys/subjects/delete-subjects', 'id' => $row['subjectid']]),
                                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                'data' => [
                                                                    'confirm' => 'Are you sure you delete this record?',
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