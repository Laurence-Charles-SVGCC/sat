<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    use frontend\models\Package;

    $this->title = 'Packages Summary';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/package']);?>" title="Manage Packages">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title">Packages Summary</span>
         <?php if (Package::getIncompletePackageID() == true):?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/package/initiate-package', 'recordid' => Package::getIncompletePackageID()]);?> role="button"> Complete-Package-Setup</a>
        <?php else:?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/package/initiate-package']);?> role="button"> Initiate-Package-Setup</a>
        <?php endif;?>
     </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'package_name',
                        'format' => 'text',
                        'label' => 'Name'
                    ],
                    [
                        'attribute' => 'period_name',
                        'format' => 'text',
                        'label' => 'App. Period'
                    ],
                    [
                        'attribute' => 'division',
                        'format' => 'text',
                        'label' => 'Division'
                    ],
                    [
                        'attribute' => 'year',
                        'format' => 'text',
                        'label' => 'Year'
                    ],
                    [
                        'attribute' => 'type',
                        'format' => 'text',
                        'label' => 'Type'
                    ],
                    [
                        'attribute' => 'progress',
                        'format' => 'text',
                        'label' => 'Progress'
                    ],
                    [
                        'attribute' => 'document_count',
                        'format' => 'text',
                        'label' => 'Docs'
                    ],
                    [
                        'attribute' => 'last_modified_by',
                        'format' => 'text',
                        'label' => 'Modified By'
                    ],
                    [
                        'label' => 'View/Edit',
                        'format' => 'html',
                        'value' => function($row)
                         {
                                if(Yii::$app->user->can('Registrar')  && Package::hasBeenPublished($row['id']) == false)
                                {
                                    return Html::a(' Edit', 
                                                        ['package/edit-package', 'recordid' => $row["id"]], 
                                                        ['class' => 'btn btn-info',]);
                                }
                                elseif(Yii::$app->user->can('Registrar')  && Package::hasBeenPublished($row['id']) == true)
                                {
                                    return Html::a(' View', 
                                                        ['package/view-package', 'recordid' => $row["id"]], 
                                                        ['class' => 'btn btn-info',]);
                                }
                                else
                                {
                                    return "N/A";
                                }
                         }
                    ],
                    [
                        'label' => 'Delete/Deactivate',
                        'format' => 'html',
                        'value' => function($row)
                         {
                                if(Yii::$app->user->can('Registrar')  && Package::hasBeenPublished($row['id']) == false)
                                {
                                    return Html::a(' Delete', 
                                                ['package/delete-package', 'recordid' => $row["id"]], 
                                                ['class' => 'btn btn-danger',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                }
                                elseif(Yii::$app->user->can('Registrar')  && Package::hasBeenPublished($row['id']) == true)
                                {
                                    return Html::a(' Deactivate', 
                                                ['package/deactivate-package', 'recordid' => $row["id"]], 
                                                ['class' => 'btn btn-danger',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                }
                                else
                                {
                                    return "N/A";
                                }
                         }
                    ],
                ],
            ]); 
        ?>
    </div>
</div>