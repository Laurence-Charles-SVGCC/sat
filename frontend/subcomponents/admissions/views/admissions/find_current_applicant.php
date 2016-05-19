<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Current Applicant Search';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="find-current-applicant">
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
            <?php
                $form = ActiveForm::begin(
                    [
                        'action' => Url::to(['admissions/find-current-applicant', 'status' => $status]),
                    ]); 
            ?>
            
                <div class="center_content general_text">
                    <?php if ($status == "pending"):?>
                        <p>
                            Welcome. This module facilitates the search for all applicants associated 
                            with the current open application periods.  
                        </p>
                    <?php else:?>
                        <p>
                            Welcome. This module facilitates the search for applicants who have been 
                            given an offer. 
                        </p> 
                    <?php endif;?>

                    <div>
                        There are three ways in which you can navigate this application.
                        <ol>
                            <li>You may begin your search based on your Applicant ID.</li>

                            <li>You may begin your search based on your Applicant Name.</li>

                            <li>You may begin your search based on your Email Address.</li>
                        </ol>
                    </div> 

                    <p class="general_text">
                        Please select a method by which to begin your search.
                        <?= Html::radioList('search_how', null, ['applicantid' => 'By Applicant ID' , 'name' => 'By Applicant Name', 'email' => 'By Email'], ['class'=> 'form_field', 'onclick'=> 'checkSearchHow();']);?>
                    </p>

                    <div id="applicantid" style="display:none">
                        <?= Html::label( 'Applicant ID',  'studentid_label'); ?>
                        <?= Html::input('text', 'applicantid_field'); ?>
                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
                    </div>

                    <div id="name" style="display:none">
                        <?= Html::label( 'First Name',  'firstname_label'); ?>
                        <?= Html::input('text', 'FirstName_field'); ?> <br/><br/>

                        <?= Html::label( 'Last Name',  'lastname_label'); ?>
                        <?= Html::input('text', 'LastName_field'); ?> 

                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
                    </div>

                    <div id="email" style="display:none">
                        <?= Html::label( 'Email',  'email_label'); ?>
                        <?= Html::input('text', 'email_field'); ?>
                        <?= Html::submitButton('Search', ['class' => 'btn btn-md btn-success', 'style' => 'float: right']) ?>
                    </div>
                </div> 
            <?php ActiveForm::end(); ?>
            
            
            <?php if ($status == "pending"  && $dataProvider == true) : ?>
                <h3><?= "Search results for: " . $info_string ?></h3>
                <?= $this->render('pending_applicants_results', [
                                    'dataProvider' => $dataProvider,
                                    'info_string' => $info_string,
                                    'status' => $status,
                                    ]
                                ) 
                ?>
            
            <?php elseif ($status == "successful"  && $dataProvider == true) : ?>
                <h3><?= "Search results for: " . $info_string ?></h3>
                <?= $this->render('successful_applicants_results', [
                                    'dataProvider' => $dataProvider,
                                    'info_string' => $info_string,
                                    'status' => $status,
                                    ]
                                ) 
                ?>
            <?php endif; ?>
            
        </div>
    </div>
</div>

