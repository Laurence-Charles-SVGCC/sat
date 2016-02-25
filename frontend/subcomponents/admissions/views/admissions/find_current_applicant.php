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
            <?php $form = ActiveForm::begin(
                [
                    'action' => Url::to(['admissions/find-current-applicant']),
                ]); 
            ?>
            
                <div class="center_content general_text">
                    <p>
                        Welcome. This application facilitates the management of all student grades.  
                    </p> 

                    <div>
                        There are two ways in which you can navigate this application.
                        <ol>
                            <li>You may begin your search based on your Applicant ID.</li>

                            <li>You may begin your search based on your Student Name.</li>

                            <li>You may begin your search based on your Email Address.</li>
                        </ol>
                    </div> 

                    <p class="general_text">
                        Please select a method by which to begin your search.
                        <?= Html::radioList('search_how', null, ['applicantid' => 'By Applicant ID' , 'name' => 'By Student Name', 'email' => 'By Email'], ['class'=> 'form_field', 'onclick'=> 'checkSearchHow();']);?>
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
            <?php if ($dataProvider) : ?>
                <h3><?= "Search results for: " . $info_string ?></h3>
                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'options' => ['style' => 'width: 95%; margin: 0 auto;'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'format' => 'html',
                            'label' => 'Applicant ID',
                            'value' => function($row)
                                {
                                   return Html::a($row['username'], 
                                                    Url::to(['process-applications/view-applicant-certificates',
                                                             'applicantid' => $row['applicantid'],
                                                             'programme' => $row['programme'], 
                                                             'application_status' => $row['application_status']
                                                            ])
                                                );
                                                   
                                }
                        ],
                        [
                            'attribute' => 'firstname',
                            'format' => 'text',
                            'label' => 'First Name'
                        ],
                        [
                            'attribute' => 'middlename',
                            'format' => 'text',
                            'label' => 'Middle Name(s)'
                        ],
                        [
                            'attribute' => 'lastname',
                            'format' => 'text',
                            'label' => 'Last Name'
                        ],
                        [
                            'attribute' => 'gender',
                            'format' => 'text',
                            'label' => 'Gender'
                        ],
                        [
                            'attribute' => 'dateofbirth',
                            'format' => 'text',
                            'label' => 'Date of Birth'
                        ],
                    ],
                ]); ?>
            <?php endif; ?>
                
        </div>
    </div>
</div>

