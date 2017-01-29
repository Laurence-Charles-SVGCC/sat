<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Application Periods Summary';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/manage-application-period']);?>" title="Manage Application Periods">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title">Packages Summary</span>
         <?php if (ApplicationPeriod::hasIncompletePeriod() == true):?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/admissions/initiate-period', 'recordid' => ApplicationPeriod::getIncompletePeriodID()]);?> role="button"> Complete-Period-Setup</a>
        <?php else:?>
            <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/admissions/initiate-period']);?> role="button"> Initiate-Period-Setup</a>
        <?php endif;?>
     </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Name'
                    ],
                    [
                        'attribute' => 'year',
                        'format' => 'text',
                        'label' => 'Year'
                    ],
                    [
                        'attribute' => 'division',
                        'format' => 'text',
                        'label' => 'Division'
                    ],
                    [
                        'attribute' => 'type',
                        'format' => 'text',
                        'label' => 'Type'
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'text',
                        'label' => 'Status'
                    ],
                    [
                        'attribute' => 'iscomplete',
                        'format' => 'text',
                        'label' => 'Visibility'
                    ],
                    [
                        'attribute' => 'offsitestartdate',
                        'format' => 'text',
                        'label' => 'Start Date'
                    ],
                    [
                        'attribute' => 'offsiteenddate',
                        'format' => 'text',
                        'label' => 'End Date'
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'text',
                        'label' => 'Creator'
                    ],
                    [
                        'label' => 'Edit',
                        'format' => 'html',
                        'value' => function($row)
                         {
                               if(Yii::$app->user->can('admissions'))
                                {
                                    return Html::a(' Edit', 
                                                        ['application-period/edit-application-period', 'recordid' => $row["id"]], 
                                                        ['class' => 'btn btn-info']);
                                }
                                else
                                {
                                    return "N/A";
                                }
                         }
                    ],
                    [
                        'label' => 'Delete',
                        'format' => 'html',
                        'value' => function($row)
                         {
                                if(Yii::$app->user->can('admissions')  && ApplicationPeriod::canSafeToDelete($row['id']) == true)
                                {
                                    echo Html::a(' Delete', 
                                ['application-period/delete-application-period', 'recordid' => $row["id"]], 
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