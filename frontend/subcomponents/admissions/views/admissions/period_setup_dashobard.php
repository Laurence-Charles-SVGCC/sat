<?php

/* 
 * Author: Laurence Charles
 * Date Created 08/02/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\UrlManager;
    
    use frontend\models\ApplicationPeriod;
    
    $this->title = 'Application Period Setup Dashboard';
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body"> 
            <h1 class="custom_h1"><?= Html::encode($this->title);?></h1>
            </br>
            
            <div style="width:75%; margin: 0 auto;">
            
                <!--Step 1 button-->
                <fieldset id="step-one">
                    <legend>Step 1: Configure Academic Year</legend>
                    <a href="<?= Url::toRoute(['admissions/period-setup-step-one'])?>" title="Configure Academic Year">
                        <!--If academic year record for new application period is confirmed as available-->
                        <?php if ($period->applicationperiodstatusid > 1):?>     
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit available academic years
                            </div>
                        <!--If academic year not yet verified as available-->
                        <?php elseif ($period->applicationperiodstatusid == 1):?>
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to verify availability of academic year
                            </div> 
                        <?php endif; ?>
                    </a>
                </fieldset></br> 
                
                
                <!--Step 2 button-->
                <fieldset id="step-two">
                    <legend>Step 2: Configure Application Period</legend>
                    <a href="<?= Url::toRoute(['admissions/period-setup-step-two'])?>" title="Configure Application Period">
                        <!--If academic year record for new application period is confirmed as available-->
                        <?php if ($period->applicationperiodstatusid > 2):?>     
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here change application period configurations
                            </div>
                        <!--If academic year not yet verified as available-->
                        <?php elseif ($period->applicationperiodstatusid == 2):?>
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here enter application period configurations
                            </div> 
                        <?php endif; ?>
                    </a>
                </fieldset></br> 
                
                
                <!--Step 3 button-->
                <fieldset id="step-three">
                    <legend>Step 3: Assign Programmes</legend>
                    <a href="<?= Url::toRoute(['admissions/period-setup-step-three'])?>" title="Add Programmes">
                        <!--If academic year record for new application period is confirmed as available-->
                        <?php if ($period->applicationperiodstatusid > 3):?>     
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to update programme selection
                            </div>
                        <!--If academic year not yet verified as available-->
                        <?php elseif ($period->applicationperiodstatusid == 3):?>
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter programme selection
                            </div> 
                        <?php endif; ?>
                    </a>
                </fieldset></br> 
                
                <?php if ($period->applicationperiodstatusid == 4 ):?>  
                    </br><p id="confirm-configuration">
                        <?= Html::a('Complete Setup', ['admissions/period-setup-confirm', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-info']) ?>
                    </p> 
                <?php endif;?>  
                
                
                
            </div>
        </div>
    </div>
</div>
            
            









