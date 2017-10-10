<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    use frontend\models\ApplicationPeriod;

    $this->title = 'Application Period Statistics';
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="alert alert-info" style = "font-size:1.1em">
    The following presents application statistics for each application period. <br/>
    If you wish to view the applicant listing which relates to the particular statistic, 
    simple click on the figure of interest.<br/>
    <strong>Category Key:</strong>  
    <ul>
        <li>Commenced = Email has been verified and applicant account created.</li>
        <li>Completed = Applicants that submitted applications.</li>
        <li>Incomplete = Applicants that have not submitted applications.</li>
        <li>Removed = Applications that have been removed.</li>
        <li>Verified = Applicants whose qualifications have been verified.</li>
        <li>Verification Incomplete = Applicants whose qualifications have been verified.</li>
    </ul>
</div>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title">Application Period Statistics</span>
         <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/applications/application-period-statistics/download-period-statistics-report']);?> role="button"> Download Report</a>
     </div>
    
    <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $period_stats_data_provider,
                'columns' => [
                    [
                        'attribute' => 'title',
                        'format' => 'text',
                        'label' => 'Year'
                    ],
                    [
                        'attribute' => 'applicantintent_name',
                        'format' => 'text',
                        'label' => 'Type'
                    ],
                    [
                        'label' => ' Commenced',
                        'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['total_number_of_applications_started'] > 0)
                            {
                                return Html::a($row['total_number_of_applications_started'], 
                                                        ['application-period-statistics/download-commenced-applications-report', 'academicyearid' => $row["academicyearid"]], 
                                                        ['title' => 'Click to download']);
                            }
                            else
                            {
                                return $row['total_number_of_applications_started'] ;
                            }
                        }
                    ],
                    [
                        'label' => 'Incomplete',
                        'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['total_number_of_applications_incomplete'] > 0)
                            {
                                return Html::a($row['total_number_of_applications_incomplete'], 
                                                        ['application-period-statistics/download-incomplete-applications-report', 'academicyearid' => $row["academicyearid"]], 
                                                        ['title' => 'Click to download']);
                            }
                            else
                            {
                                return $row['total_number_of_applications_incomplete'];
                            }
                        }
                    ],
                    [
                        'label' => 'Completed',
                        'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['total_number_of_applications_completed'] > 0)
                            {
                                return Html::a($row['total_number_of_applications_completed'], 
                                                        ['application-period-statistics/download-completed-applications-report', 'academicyearid' => $row["academicyearid"]], 
                                                        ['title' => 'Click to download']);
                            }
                            else
                            {
                                return $row['total_number_of_applications_completed'];
                            }
                        }
                    ],
                    [
                        'attribute' => 'total_number_of_applications_removed',
                        'format' => 'text',
                        'label' => 'Removed'
                    ],
                    [
                        'label' => 'Verified',
                        'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['total_number_of_applications_verified'] > 0)
                            {
                                return Html::a($row['total_number_of_applications_verified'], 
                                                        ['application-period-statistics/download-verified-applications-report', 'academicyearid' => $row["academicyearid"]], 
                                                        ['title' => 'Click to download']);
                            }
                            else
                            {
                                return $row['total_number_of_applications_verified'];
                            }
                        }
                    ],
                    [
                        'label' =>'Verification Incomplete',
                         'format' => 'html',
                        'value' => function($row)
                        {
                            if ($row['total_number_of_applications_unverified'] > 0)
                            {
                                return Html::a($row['total_number_of_applications_unverified'], 
                                                        ['application-period-statistics/download-unverified-applications-report', 'academicyearid' => $row["academicyearid"]], 
                                                        ['title' => 'Click to download']);
                            }
                            else
                            {
                                return $row['total_number_of_applications_unverified'];
                            }
                        }
                    ],
                ],
            ]); 
        ?>
    </div>
</div>