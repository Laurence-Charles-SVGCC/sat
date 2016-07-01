<?php

/* 
 * Author: Laurence Charles
 * Date Created: 17/05/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    /* @var $this yii\web\View */
    $this->title = 'Account Creation Dashboard';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/create_male.png" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/create_female.png" alt="student avatar" class="pull-right">
                </a>   
            </div>
        
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title?></h1>
                <br/>
                
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
    </div>