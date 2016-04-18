<?php

/* 
 * Author: Laurence Charles
 * Date Created 08/02/2016
 */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\UrlManager;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Package;
    
    $this->title = 'Package Setup Dashboard';
?>


<div class="site-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        
        <div class="custom_body"> 
            <h1 class="custom_h1"><?= Html::encode($this->title);?></h1>
            </br>
            
            <div style="width:75%; margin: 0 auto;">
            
                <!--Step 1 button-->
                <fieldset id="package-step-one">
                    <legend>Step 1: Configure Package</legend>
                    <!--If there exists an incomplete package that is not fully configured-->
                    <?php if ($recordid):?> 
                        <a href="<?= Url::toRoute(['package/initialize-package', 'recordid' => $recordid])?>" title="Configure Academic Year">
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit package configurations
                            </div>
                        </a>
                    <!--If there are no packages awaiting completion-->
                    <?php else:?>
                        <a href="<?= Url::toRoute(['package/initialize-package'])?>" title="Configure Academic Year">
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter package configurations
                            </div> 
                        </a>
                    <?php endif; ?>
                </fieldset></br> 
                
                <?php if ($recordid == true  && Package::needsToUpload($recordid) == true &&  $package->packageprogressid >= 1):?> 
                    <!--Step 2 button-->
                    <fieldset id="package-step-two">
                        <legend>Step 2: Upload Documents</legend>
                        <a href="<?= Url::toRoute(['package/upload-attachments', 'recordid' => $recordid, 'count' => $package->documentcount])?>" title="Upload Documents">
                            <!--All indicated documents have been uploaded-->
                            <?php if ($package->packageprogressid > 1 || Package::assessDocuments() == 0):?>     
                                <div class="alert in alert-block fade alert-success mainButtons">
                                    Click here modify attachments
                                </div>
                            <!--If academic year not yet verified as available-->
                            <?php elseif ($package->packageprogressid == 1):?>
                                <div class="alert in alert-block fade alert-error mainButtons">
                                    Click here upload attachments
                                </div> 
                            <?php endif; ?>
                        </a>
                    </fieldset></br> 
                <?php endif; ?>
                
                <?php if ($recordid == true  &&  $package->packageprogressid >= 2):?>    
                    <!--Step 3 button-->
                    <fieldset id="package-step-three">
                        <legend>Step 3: Test Package</legend>
                        <a href="<?= Url::toRoute(['package/test-package', 'recordid' => $recordid])?>" title="Test Package">
                            <!--If academic year record for new application period is confirmed as available-->
                            <?php if ($package->packageprogressid > 2):?>     
                                <div class="alert in alert-block fade alert-success mainButtons">
                                    Click here to retest package
                                </div>
                            <!--If academic year not yet verified as available-->
                            <?php elseif ($package->packageprogressid == 2):?>
                                <div class="alert in alert-block fade alert-error mainButtons">
                                    Click here run initial package test
                                </div> 
                            <?php endif; ?>
                        </a>
                    </fieldset></br> 
                <?php endif; ?>
                    
                <?php if ($recordid == true  &&  $package->packageprogressid == 3 ):?>  
                    </br><p id="confirm-configuration">
                        <?= Html::a('Complete Setup', ['package/confirm-package', 'recordid' => $recordid], ['class' => 'btn btn-block btn-lg btn-info']) ?>
                    </p> 
                <?php endif;?>  
               
                
            </div>
        </div>
    </div>
</div>
            
            









