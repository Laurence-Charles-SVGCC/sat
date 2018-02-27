 <?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\UrlManager;
    
    $this->title = 'Setup Dashboard';
    $this->params['breadcrumbs'][] = ['label' => 'Period Listing', 'url' => Url::toRoute(['/subcomponents/applications/application-periods/view-periods'])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

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
        </fieldset><br/> 

        
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
            </fieldset><br/> 
        <?php endif; ?>

  
        <?php if ($period->applicationperiodstatusid > 2):?> 
             <!--Step 3 button-->
             <?php if ($period->catalog_approved == false):?>     
                <fieldset id="step-three">
                    <legend>Step 3: Programme Catalog Approval</legend>
                    <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-three'])?>" title="Approval Catalog">
                        <div class="alert in alert-block fade alert-error mainButtons">
                           Click here verify programme offerings available for selection
                        </div>
                    </a>
                </fieldset><br/> 
            <?php else: ?>
                <fieldset id="step-three">
                    <legend>Step 3: Programme Catalog Approval</legend>
                    <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-three'])?>" title="Approval Catalog">
                        <div class="alert in alert-block fade alert-success mainButtons">
                            Click here verify programme offerings available for selection
                        </div>
                    </a>
                </fieldset><br/> 
                
                
                <!--Step 4 button-->
                <?php if ($period->programmes_added == false):?>     
                   <fieldset id="step-four">
                       <legend>Step 4: Assign Programmes</legend>
                       <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-four'])?>" title="Assign Programmes">
                           <div class="alert in alert-block fade alert-error mainButtons">
                               Click here to enter programme selection
                           </div>
                       </a>
                   </fieldset><br/> 
               <?php else: ?>
                   <fieldset id="step-four">
                       <legend>Step 4: Assign Programmes</legend>
                       <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-four'])?>" title="Assign Programmes">
                           <div class="alert in alert-block fade alert-success mainButtons">
                               Click here to update programme selection
                           </div>
                       </a>
                   </fieldset><br/>
                   
                   
                   <!--Step 5 button-->
                   <?php if ($period->divisionid  == 4 && $cape_offering_selected == true):?>  
                        <?php if ($period->cape_subjects_added == false):?>     
                            <fieldset id="step-five">
                                <legend>Step 5: Assign Cape Subjects</legend>
                                <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-five'])?>" title="Assign Subjects">
                                    <div class="alert in alert-block fade alert-error mainButtons">
                                        Click here to enter cape subject selection
                                    </div>
                                </a>
                            </fieldset><br/> 
                        <?php else: ?>
                            <fieldset id="step-four">
                                <legend>Step 4: Assign Cape Subjects</legend>
                                <a href="<?= Url::toRoute(['manage-application-periods/period-setup-step-five'])?>" title="Assign Subjects">
                                    <div class="alert in alert-block fade alert-success mainButtons">
                                        Click here to update cape subject selection
                                    </div>
                                </a>
                            </fieldset><br/>
                        <?php endif; ?>
                    <?php endif; ?><!-- end step 5 -->
               <?php endif; ?><!-- end step 4 -->
            <?php endif; ?><!-- end step 3 -->
        <?php endif;?>
        
        
        <?php if ($period->applicationperiodstatusid == 4 ):?>  
            <br/><p id="confirm-configuration">
                <?= Html::a('Complete Setup', ['manage-application-periods/period-setup-confirm', 'recordid' => $period->applicationperiodid], ['class' => 'btn btn-block btn-lg btn-info']) ?>
            </p> 
        <?php endif;?>  
    </div>
</div>