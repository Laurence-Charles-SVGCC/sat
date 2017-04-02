<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\DetailView;

    if ($type == 1)
    {
        $this->title = "Roles Information";
        $this->params['breadcrumbs'][] = ['label' => 'Roles Listing', 'url' => ['index', 'type' => 'Roles']];
    }
    elseif ($type == 2)
    {
        $this->title = "Permission Information";
        $this->params['breadcrumbs'][] = ['label' => 'Permissions Listing', 'url' => ['index', 'type' => 'Permissions']];
    }
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
        <?php if ($type == 1):?>
            <span class="box-title">Role Details</span>
        <?php elseif ($type == 2):?>
            <span class="box-title">Permission Details</span>
        <?php endif;?>
    </div>
    
    <div class="box-body">
        <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:ntext',
                ],
            ]) 
        ?>
    </div>
</div>

<?php if ($type == 1):?>
    <br/><br/>
    <div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
        <div class="box-header with-border">
            <span class="box-title">Associated Permissions</span>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['auth-item-child/add-permission-to-role', 'name'=> $name, 'type' => $type]);?> role="button"> Add New Permission</a>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                    'dataProvider' => $permission_dataProvider,
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
                    ],
                ]); 
            ?>
        </div>
    </div>
<?php endif;?>