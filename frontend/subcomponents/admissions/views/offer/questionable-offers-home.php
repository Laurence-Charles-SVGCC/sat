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
                <h2>Categories of Questionable offers</h2>
                <ul>
                    <?php if($math_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
                                title="Lack CSEC Mathematics Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC Mathematics
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($english_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
                                title="Lack CSEC English Pass"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicant lacking CSEC English Language
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($subjects_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
                                title="Lack 5 CSEC Passes"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking five(5) CSEC passes
                             </a>
                        </li><br/>
                    <?php endif;?>
                    
                    <?php if($dte_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
                                title="Lack DTE Required Reelvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DTE's the required relevant science
                             </a>
                        </li><br/>
                    <?php endif;?>
                        
                    <?php if($dne_science_req):?>
                        <li>
                            <a href="<?= Url::toRoute(['/subcomponents/admissions/offer/offer-issue-details']);?>" 
                                title="Lack DNE Required Relvant Science"
                                style="font-size:16px; width: 65%; margin: 0 auto; color:white" class ='btn btn-danger'> 
                                Click here to view successful applicants lacking DNE's required relevant science
                             </a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
            
        </div>
    </div>
</div>