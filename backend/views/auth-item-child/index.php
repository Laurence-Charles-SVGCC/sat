<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

    $this->title = 'Role Hierarchies';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-item-child/index']);?>" title="Access Management Home">
        <h1>Welcome to the Access Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <span class="box-title">Role Assignments</span>
        <a class="btn btn-info pull-right" href=<?=Url::toRoute(['create']);?> role="button"> Create Role-Role Assignment</a>
    </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'parent',
                        'format' => 'text',
                        'label' => 'Parent'
                    ],
                    [
                        'attribute' => 'child',
                        'format' => 'text',
                        'label' => 'Child'
                    ],
                    [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'Action',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $row) 
                        {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                Url::to(['update', 'parent' => $row['parent'], 'child' => $row['child']]),
                                ['title' => 'Update']);
                        },
                        'delete' => function ($url, $row) 
                         {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::to(['auth-assignment/delete',  'parent' => $row['parent'], 'child' => $row['child']]),
                                ['title' => 'Delete']);
                        },
                    ],
                ],    
                ],
            ]); 
        ?>
    </div>
</div>
