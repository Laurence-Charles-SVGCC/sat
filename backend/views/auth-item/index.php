<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    use backend\models\AuthAssignment;
    use backend\models\AuthItemChild;

    $this->title = $type;
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/auth-item/index', 'type' => $type]);?>" title="User Management Home">
        <h1>Welcome to the User Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
    <div class="box-header with-border">
        <span class="box-title"><?= $type . " Listing" ;?></span>
        <?php if ($type == "Roles"):?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['create', 'type'=>1]);?> role="button"> Create New Role</a>
       <?php elseif ($type == "Permissions"):?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['create', 'type'=>2]);?> role="button"> Create New Permission</a>
       <?php endif;?>
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
                        'attribute' => 'description',
                        'format' => 'text',
                        'label' => 'Description'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'Action',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $row) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['auth-item/view', 'name' => $row['name'], 'type' => $row['type']]),
                                    ['title' => 'View']
                                   );
                            },
                            'update' => function ($url, $row) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    Url::to(['auth-item/update', 'name' => $row['name'], 'type' => $row['type']]),
                                    ['title' => 'Update']
                                   );
                            },
                            'delete' => function ($url, $row) {
                                //if auth_item is role and role is not yet assigned to user and it is not a parent role 
                                if ($row['type'] == 1  && 
                                        AuthAssignment::find()->where(['item_name' => $row['name']])->count() == 0  &&
                                        AuthItemChild::find()->where(['parent' => $row['name']])->count() == 0)
                                {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-trash"></span>',
                                        Url::to(['auth-item/delete', 'name' => $row['name'], 'type' => $row['type']]),
                                        ['title' => 'Delete']
                                       );
                                }
                                //if auth_item is role and role is not yet assigned to user and it is not a parent role 
                                elseif ($row['type'] == 2  && AuthItemChild::find()->where(['child' => $row['name']])->count() == 0)
                                {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-trash"></span>',
                                        Url::to(['auth-item/delete', 'name' => $row['name'], 'type' => $row['type']]),
                                        ['title' => 'Delete']
                                       );
                                }
                            },
                        ],
                    ],    
                ],
            ]); 
        ?>
    </div>
</div>

