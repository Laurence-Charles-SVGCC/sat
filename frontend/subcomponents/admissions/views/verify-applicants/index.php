<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use frontend\models\Application;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Verify Applicants';
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
        
        <p id="offer-message" class="alert alert-info" role="alert" style="width: 95%; margin: 0 auto; font-size:16px;"> 
            <strong>Total Application Received: <?= Application::countActiveApplications();?></strong>
        </p>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
           
            <?php if ($dataProvider == true):?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
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
                                           Url::to(['verify-applicants/centre-details', 'centre_id' => $row['centre_id'], 'centre_name' => $row['centre_name']]));
                                }
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'text',
                            'label' => 'Status'
                        ],
                        [
                            'attribute' => 'applicants_verified',
                            'format' => 'text',
                            'label' => 'Applicants Verified'
                        ],
                        [
                            'attribute' => 'total_received',
                            'format' => 'text',
                            'label' => 'Total Received Applicants'
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Percentage Completed',
                            'value' => function($row)
                                {
                                        $value = $row['percentage_completed'];
                                       return 
                                        "<small class='pull-right'>$value%</small>
                                         <div class='progress xs'>
                                          <div class='progress-bar progress-bar-green' style='width: $value%' role='progressbar' aria-valuenow='$value' aria-valuemin='0' aria-valuemax='100'>
                                            <span class='sr-only'>$value%</span>
                                          </div>
                                         </div>
                                        ";
                                }
                        ],
                    ],
                ]); ?>
            
            <?php else:?>
                <br/><div class="alert alert-info" role="alert" style="width: 90%; margin: 0 auto; font-size:20px; text-align:center">
                    <p>There are no current applications, pending verification.</p>
                </div>
            <?php endif;?>
        </div>
    </div>

</div>