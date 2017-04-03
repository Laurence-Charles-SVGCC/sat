<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\DetailView;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    use backend\models\AuthItemChild;
    use backend\models\AuthItem;

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
        </div>

        <div class="box-body">
            <p>You are only permitted to delete <strong>permissions</strong> that are directly associated with a particular <strong>role</strong>.</p>
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
                        [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'Action',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $row) {
                                //if permission is directly associated with the role in question
                                if (AuthItemChild::find()->where(['parent' => $row['role_name'], 'child' => $row['name']])->one() == true)
                                {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-trash"></span>',
                                        Url::to(['auth-item/delete-permission-from-role', 'name' => $row['role_name'], 'type' => 1, 'permission_name' => $row['name']]),
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
<?php endif;?>

    
 <br/><br/>
<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;  width:99%; margin: 0 auto;">
        <div class="box-header with-border">
            <span class="box-title">Add Permission</span>
        </div>   
    
        <?php $form = ActiveForm::begin(
                ['action' => Url::to(['auth-item/assign-new-permission-to-role', 'name' => $name, 'type' => $type])]); 
        ?>
            <div class="box-body">
                <div class="form-group">
                   <label class="control-label col-xs-6 col-sm-5 col-md-5 col-lg-3" for="name">New Permission:</label>
                   <?= $form->field($new_permission, 'child')->label('')->dropDownList(ArrayHelper::map(AuthItem::find()->where(['type' => 2])->andWhere(['not', ['name' => $permissions ]])->all(), 'name', 'name'), ['prompt'=>'Select Permission..', "class" => "no-padding col-xs-6 col-sm-7 col-md-7 col-lg-9"]) ?>
               </div>
            </div>

            <div class="box-footer pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
            </div>
        <?php ActiveForm::end(); ?>
 </div>