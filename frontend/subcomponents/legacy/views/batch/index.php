<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

     $this->title = 'Batch Listing';
     $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/legacy/batch/index']);?>" title="Legacy Batches">
        <h1>Welcome to the Legacy Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <h2 class="text-center"><?= $this->title?></h2>
    
    <?php if (true/*Yii::$app->user->can('createLegacyBatch')*/): ?>
        <div class="box-header with-border">
            <?= Html::a('Create Batch', ['batch/create'], ['class' => 'btn btn-info pull-right']) ?>
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
                        'attribute' => 'year',
                        'format' => 'text',
                        'label' => 'Year'
                    ],
                    [
                        'attribute' => 'term',
                        'format' => 'text',
                        'label' => 'Term'
                    ],
                    [
                        'attribute' => 'level',
                        'format' => 'text',
                        'label' => 'Level'
                    ],
                    [
                        'attribute' => 'student_count',
                        'format' => 'text',
                        'label' => 'Student Count'
                    ],
                ],
            ]); 
       ?>
    </div>
</div>