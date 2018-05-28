 <?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\UrlManager;
    
    $this->title = 'Setup Dashboard';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body" style="width:80%; margin: 0 auto;">
        <!--Step 1 button-->
        <fieldset id="step-one">
            <legend>Step 1: Configure Academic Year</legend>
            <?php if ($period->applicationperiodstatusid > 1):?>  
                <!--If academic year record for new application period is confirmed as available-->

                    <div class="alert in alert-block fade alert-success mainButtons">
                        Academic year configured
                    </div>

            <?php elseif ($period->applicationperiodstatusid == 1):?>
                <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-one'])?>" title="Configure Academic Year">
                    <div class="alert in alert-block fade alert-error mainButtons">
                        Configure academic year
                    </div> 
                </a>
             <?php endif; ?>
        </fieldset></br> 

        
        <?php if ($period->applicationperiodstatusid > 1):?>     
            <!--Step 2 button-->
            <fieldset id="step-two">
                <legend>Step 2: Configure Application Period</legend>
                <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-two'])?>" title="Configure Application Period">
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
        <?php endif; ?>

            
        <?php if ($period->applicationperiodstatusid > 2):?>     
            <!--Step 3 button-->
            <fieldset id="step-three">
                <legend>Step 3: Assign Programmes</legend>
                <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-three'])?>" title="Add Programmes">
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
        <?php endif;?>
        
        
        <?php if ($period->applicationperiodstatusid == 4 ):?>  
            </br><p id="confirm-configuration">
                <?= Html::a('Complete Setup', ['manage-application-periods/period-setup-confirm', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-info']) ?>
            </p> 
        <?php endif;?>  
    </div>
</div>