<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    $this->title = 'Account Creation Dashboard';
    $this->params['breadcrumbs'][] = ['label' => 'Student Listing', 'url' => Url::toRoute(['/subcomponents/students/account-management'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary"  style="font-size:1.1em">
     <div class="box-header with-border">
         <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">
        <div style="width:75%; margin: 0 auto;">
            <!--Step 1 button-->
            <fieldset id="account-step-one">
                <legend>Step 1: Initialize Account</legend>
                <?php if ($recordid == NULL):?> 
                    <a href="<?= Url::toRoute(['account-management/initialize-account'])?>" title="Initialize Account">
                        <div class="alert in alert-block fade alert-error mainButtons">
                            Click here to enter initialize account.
                        </div>
                    </a>
                <?php else:?>
                    <a title="Initialize Account">
                        <div class="alert in alert-block fade alert-success mainButtons">
                            Account initialization complete.
                        </div>
                    </a>
                <?php endif; ?>
            </fieldset></br> 

            <?php if ($progress >= 1):?> 
                <fieldset id="account-step-two">
                    <legend>Step 2: Student Profile</legend>
                    <?php if ($progress == 1):?> 
                        <a href="<?= Url::toRoute(['account-management/profile', 'recordid' => $recordid])?>" title="Profile Information">
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter profile information.
                            </div>
                        </a>
                    <?php elseif($progress > 1):?>
                        <a href="<?= Url::toRoute(['account-management/profile', 'recordid' => $recordid])?>" title="Profile Information">
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit profile information.
                            </div>
                        </a>
                    <?php endif; ?>
                </fieldset></br> 
            <?php endif; ?>

            <?php if ($progress >= 2):?> 
                <fieldset id="account-step-three">
                    <legend>Step 3: Student Contacts</legend>
                    <?php if ($progress == 2):?> 
                        <a href="<?= Url::toRoute(['account-management/contacts', 'recordid' => $recordid])?>" title="Student Contacts">
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter contacts information.
                            </div>
                        </a>
                    <?php elseif($progress > 2):?>
                        <a href="<?= Url::toRoute(['account-management/contacts', 'recordid' => $recordid])?>" title="Student Contacts">
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit contacts information.
                            </div>
                        </a>
                    <?php endif; ?>
                </fieldset></br> 
            <?php endif; ?>

            <?php if ($progress >= 3):?> 
                <fieldset id="account-step-four">
                    <legend>Step 4: Student Address</legend>
                    <?php if ($progress == 3):?> 
                        <a href="<?= Url::toRoute(['account-management/address', 'recordid' => $recordid])?>" title="Student Address">
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter student address information.
                            </div>
                        </a>
                    <?php elseif($progress > 3):?>
                        <a href="<?= Url::toRoute(['account-management/address', 'recordid' => $recordid])?>" title="Student Address">
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit student address information.
                            </div>
                        </a>
                    <?php endif; ?>
                </fieldset></br> 
            <?php endif; ?>    

            <?php if ($progress >= 4):?> 
                <fieldset id="account-step-four">
                    <legend>Step 5: Enter Programme</legend>
                    <?php if ($progress == 4):?> 
                        <a href="<?= Url::toRoute(['account-management/programme', 'recordid' => $recordid])?>" title="Programme Selection">
                            <div class="alert in alert-block fade alert-error mainButtons">
                                Click here to enter programme.
                            </div>
                        </a>
                    <?php elseif($progress > 4):?>
                        <a href="<?= Url::toRoute(['account-management/programme', 'recordid' => $recordid])?>" title="Programme Selection">
                            <div class="alert in alert-block fade alert-success mainButtons">
                                Click here to edit edit programme.
                            </div>
                        </a>
                    <?php endif; ?>
                </fieldset></br> 
            <?php endif; ?>    
        </div>
    </div>
</div>