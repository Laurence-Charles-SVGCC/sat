<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

    //use frontend\models\Employee;

    $this->title = 'Role Assignments';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-assignment/index']);?>" title="Access Management Home">
        <h1>Welcome to the Access Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <span class="box-title">Role Assignments</span>
        <a class="btn btn-info pull-right" href=<?=Url::toRoute(['create']);?> role="button"> Assign Role</a>
    </div>
    
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
                        'attribute' => 'description',
                        'format' => 'text',
                        'label' => 'Description'
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
                                Url::to(['update', 'item_name' => $row['name'], 'user_id' => $row['personid']]),
                                ['title' => 'Update']);
                        },
                        'delete' => function ($url, $row) 
                         {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::to(['auth-assignment/delete']),
                                ['title' => 'Delete']);
                        },
                    ],
                ],    
                ],
            ]); 
        ?>
    </div>
</div>


