<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

    use frontend\models\Application;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Division;
    use frontend\models\EmployeeDepartment;

    $this->title = 'Abandoned Application Listing';
    
    $this->params['breadcrumbs'][] = ['label' => 'Examination Centre Listing', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title;?></h2>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Open Application Period Statistics</span>
     </div>
    
    <div class="box-body">
        <?php if (EmployeeDepartment::getUserDivision() == 1):?>
            <p id="offer-message" class="alert alert-info" role="alert" style="width: 98%; margin: 0 auto; font-size:16px; padding-top:15px; padding-bottom:30px;"> 
                <span class="pull-left"><strong >Total Applicants Received: <?= Application::countActiveApplications();?></strong></span>
                <span class="pull-right"><strong>Total Applicants Verified: <?= Application::countVerifiedApplications();?></strong></span>
            </p><br/>
        <?php endif;?>
            
        <?php
            $periods = ApplicationPeriod::periodIncomplete();
            if ($periods == true)
            {
                foreach ($periods as $period) 
                {
                    if (EmployeeDepartment::getUserDivision() == 1  || EmployeeDepartment::getUserDivision() == $period->divisionid)
                    {
                        echo "<p class='alert alert-info' role='alert' style='width: 98%; margin: 0 auto; font-size:16px; padding-top:15px; padding-bottom:30px;'>"; 
                            echo "<span class='pull-left'><strong >";
                                echo  Division::getDivisionAbbreviation($period->divisionid) . " Applications Received:" . Application::countActiveApplications($period->divisionid);
                            echo "</strong></span>";

                            echo "<span class='pull-right'><strong >";
                                echo Division::getDivisionAbbreviation($period->divisionid) . " Applications Verified:" . Application::countVerifiedApplications($period->divisionid);
                            echo "</strong></span>";
                        echo "</p><br/>";
                    }
                }
            }
       ?>
    </div>
</div><br/>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title">Examination Centre Listing</span>
     </div>
    
    <div class="box-body">
        <?php if ($dataProvider == true):?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                        'style' => ''
                    ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'centre_name',
                        'format' => 'html',
                        'label' => 'Centre Name',
                        'value' => function($row)
                            {
                               return Html::a($row['centre_name'], 
                                       Url::to(['verify-applicants/abandoned-centre-details', 'centreid' => $row['centre_id'], 'centrename' => $row['centre_name']]));
                            }
                    ],
                    [
                        'attribute' => 'total_received',
                        'format' => 'text',
                        'label' => 'Total Received Applicants'
                    ],
                ],
            ]); ?>

        <?php else:?>
            <br/><div class="alert alert-info" role="warning" style="width: 90%; margin: 0 auto; font-size:20px; text-align:center">
                <p>There are no abandoned applications, pending verification.</p>
            </div>
        <?php endif;?>
    </div>
    
    <div class="box-body pull-right">
        <a  class="btn btn-danger" href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>">Back</a> 
    </div>
</div>