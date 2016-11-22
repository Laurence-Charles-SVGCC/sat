<?php

/* 
 * Author: Laurence Charles
 * Date Created: 09/09/2016
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    $this->title = 'Transfers and Deferrals';
?>
    
    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>        
            </div>
          
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title;?></h1>

                <p class="general_text" style="margin-left:2.5%">
                    Please select which report you wish to view.
                    <?= Html::radioList('listing_category', null, ['transfers' => 'Transfers',  'pre-registration-deferrals' => 'Pre-Registration Deferrals',  'post-registration-deferrals' => 'Post-Registration Deferrals'], ['class'=> 'form_field', 'onclick'=> 'checkTransferOrDeferral();', 'style' => 'margin-left:2.5%']);?>
                </p>

                <div id="transfers" style="display:none">
                    <hr>
                    <h2 class="custom_h2">Transfers</h2>
                    <?php if ($transfers_provider) : ?>
                        <p style="margin-left: 2.5%">
                            Click the following button to download a copy of the transfer listing.
                            <?= Html::a('Download Transfers', ['export-transfers'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                        </p>
                        
                        <?= $this->render('transfer_results', [
                            'dataProvider' => $transfers_provider,
                        ]) ?>
                    <?php else:?>
                        <p style="margin-left:2.5%"><strong>There are no recorded student transfers.</strong></p>
                    <?php endif; ?>
                </div>
                
                <div id="pre-registration-deferrals" style="display:none">
                    <hr>
                    <h2 class="custom_h2">Pre-Registration Deferrals</h2>
                    <?php if ($pre_registration_deferrals_provider) : ?>
                        <p style="margin-left: 2.5%">
                            Click the following button to download a copy of the deferral listing.
                            <?= Html::a('Download Deferrals', ['export-pre-registration-deferrals'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                        </p>
                        
                        <?= $this->render('pre_registration_deferral_results', [
                            'dataProvider' => $pre_registration_deferrals_provider,
                        ]) ?>
                    <?php else:?>
                        <p style="margin-left:2.5%"><strong>There are no recorded student pre-registration deferrals.</strong></p>
                    <?php endif; ?>
                </div>
                
                <div id="post-registration-deferrals" style="display:none">
                    <hr>
                    <h2 class="custom_h2">Post-Registration Deferrals</h2>
                    <?php if ($post_registration_deferrals_provider) : ?>
                        <p style="margin-left: 2.5%">
                            Click the following button to download a copy of the deferral listing.
                            <?= Html::a('Download Deferrals', ['export-post-registration-deferrals'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 2.5%']) ?>
                        </p>
                        
                        <?= $this->render('post_registration_deferral_results', [
                            'dataProvider' => $post_registration_deferrals_provider,
                        ]) ?>
                    <?php else:?>
                        <p style="margin-left:2.5%"><strong>There are no recorded student post-registration deferrals.</strong></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>