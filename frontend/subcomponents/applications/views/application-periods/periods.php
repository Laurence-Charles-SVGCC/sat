<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Application Periods';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="alert alert-info" style = "font-size:1.1em">
    The following presents a summary of all configured application periods. <br/>
    <strong>Please Note:</strong> 
    <ul>
        <li>The deletion of an application period is only allowed no student applications are associated with that period.</li>
        <li>Status = "Open" indicates that applications can be submitted for that period.</li>
        <li>Status = "Closed" indicates that no further applications can be submitted for that period.</li>
        <li>Visibility = "Selectable" indicates that applicants to that application period will be included in a 'Current Applicant' search request.</li>
        <li>Visibility = "Excluded" indicates that applicants to that application period will be excluded from any 'Current Applicant' search request.</li>
    </ul>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title">Application Periods Listing</span>
         <?php if ($unconfigured_period == true):?>
            <a class="btn btn-warning pull-right" href=<?=Url::toRoute(['/subcomponents/applications/manage-application-periods/initiate-period', 'id' => $unconfigured_period->applicationperiodid]);?> role="button"> Complete-Period-Setup</a>
        <?php else:?>
            <a class="btn btn-success pull-right" href=<?=Url::toRoute(['/subcomponents/applications/manage-application-periods/initiate-period']);?> role="button"> Initiate-Period-Setup</a>
        <?php endif;?>
     </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $period_details_data_provider,
                'columns' => [
                    [
                        'label' => 'Name',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            return Html::a($row['name'], 
                                ['manage-application-periods/view-application-period', 
                                    'id' => $row["id"]]);
                         }
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
                        'label' => 'Applicant Visibility'
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
                        'label' => 'Delete',
                        'format' => 'html',
                        'value' => function($row)
                         {
                            if (ApplicationPeriod::eligibleToDelete($row['id']) == true)
                            {
                                return Html::a(' Delete', 
                                        ['applications/manage-application-periods', 'recordid' => $row["id"]], 
                                        ['class' => 'btn btn-danger',
                                            'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'post',]]);
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
</div><br/>