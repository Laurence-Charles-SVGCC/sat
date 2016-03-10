<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionable Offers Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            <div style="margin-left:2.5%">
                <h2 class="custom_h2">Categories of Questionable Offers:</h2>
                <ul>
                    <?php if($multiple_offers):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'mult']);?>" 
                                title="Multiple offers"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants with multiple offers
                            </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($math_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'maths']);?>" 
                                title="Lack CSEC Mathematics Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC Mathematics
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($english_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'english']);?>" 
                                title="Lack CSEC English Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC English Language
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($subjects_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'five_passes']);?>" 
                                title="Lack 5 CSEC Passes"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking five(5) CSEC passes
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($dte_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'dte']);?>" 
                                title="Lack DTE Required Reelvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DTE's the required relevant science
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($dne_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-details-home', 'criteria' => 'dne']);?>" 
                                title="Lack DNE Required Relvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DNE's required relevant science
                             </a>
                        </li>
                    <?php endif;?>
                </oul>
            </div>
            
            <?php if($dataProvider):?>
                <h3 style="margin-left:2.5%"><?=$offer_type?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                        'columns' => [
                            [
                                'attribute' => 'username',
                                'format' => 'html',
                                'value' => function($row)
                                 {
                                    return Html::a($row['username'], 
                                               Url::to(['offer/view', 'id' => $row['offerid']]));
                                  }
                            ],
                            'firstname',
                            'lastname',
                            'programme',
                            'issuedby',
                            'issuedate',
                            'revokedby',
                            'revokedate',
                            [
                                'attribute' => 'ispublished',
                                'format' => 'boolean',
                                'label' => 'Published'
                            ],
                        ],
                    ]); 
                ?>
            <?php endif;?>
            
        </div>
    </div>
</div>