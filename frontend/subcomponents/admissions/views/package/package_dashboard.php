<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\UrlManager;
    
    use frontend\models\ApplicationPeriod;
    use frontend\models\Package;
    
    $this->title = 'Setup Dashboard';
    
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body" style="width:80%; margin: 0 auto;">
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