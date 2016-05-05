<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use frontend\models\Application;
use frontend\models\ApplicationPeriod;
use frontend\models\Division;
use frontend\models\EmployeeDepartment;

$this->title = 'Abandoned Application Listing';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div><br/>
        
        <?php if (EmployeeDepartment::getUserDivision() == 1):?>
            <p id="offer-message" class="alert alert-info" role="alert" style="width: 95%; margin: 0 auto; font-size:16px; padding-top:15px; padding-bottom:30px;"> 
                <span class="pull-right"><strong >Total Applications Abandoned: <?= Application::countAbandonedApplications();?></strong></span>
            </p><br/>
        <?php endif;?>
        
        <?php if ($dataProvider == true):?>
            <?php
                $periods = ApplicationPeriod::periodIncomplete();
                if ($periods == true)
                {
                    foreach ($periods as $period) 
                    {
                        if (EmployeeDepartment::getUserDivision() == 1  || EmployeeDepartment::getUserDivision() == $period->divisionid)
                        {
                            echo "<p class='alert alert-info' role='alert' style='width: 95%; margin: 0 auto; font-size:16px; padding-top:15px; padding-bottom:30px;'>"; 
                                echo "<span class='pull-right'><strong >";
                                    echo  Division::getDivisionAbbreviation($period->divisionid) . " Applications Abandoned:" . Application::countAbandonedApplications($period->divisionid);
                                echo "</strong></span>";
                            echo "</p><br/>";
                        }
                    }
                }
           ?>
        <?php endif;?>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
           
            <?php if ($dataProvider == true):?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [
                            'style' => 'width:95%; margin: 0 auto;'
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
                
            <br/><br/>
            <a  class="btn btn-warning pull-right" style="width: 15%;margin-right:5%" href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants'])?>"><i ></i>Back</a> 
        </div>
    </div>

</div>

