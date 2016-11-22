<?php

/* 
 * 'Applicant_Profile' view 
 * Author: Laurence Charles
 * Date Created: 28/02/2015
 */
    
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;
    
    use frontend\models\CapeSubject;
    use frontend\models\Application;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Employee;
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\NurseWorkExperience;
    use frontend\models\CriminalRecord;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\ExternalQualification;
    use frontend\models\ConcurrentApplicant;
    use frontend\models\Applicant;
    use common\models\User;
    
    
    $relation_count = [
                        0 => '0',
                        1 => '1', 
                        2 => '2', 
                        3 => '3', 
                        4 => '4',
                        5 => '5',
                        6 => '6',
                        7 => '7',
                        8 => '8',
                        9 => '9',
                        10 => '10',
                    ];
    
    $has_worked = [
                    1 => 'Yes',
                    0 => 'No'
                ];
    
    $is_working = [
                    1 => 'Yes', 
                    0 => 'No'
                ];
    
    $nursing_experience = [
                    1 => 'Yes', 
                    0 => 'No'
                ];
    
    $teaching_experience = [
                    1 => 'Yes', 
                    0 => 'No'
                ];
    
    $financing_options = [
        'Father' => 'Father',
        'Mother' => 'Mother',
        'Self' => 'Self',
        'Other' => 'Other'
    ];
    
    $student_loan = [
        1 => 'Yes', 
        0 => 'No'
    ];
    
    $sponsorship_request = [
        'Grant' => 'Grant',
        'Sponsohip' => 'Sponsoship',
        'Both' => 'Both',
        'Neither' => 'Neither'
    ];
    
    $iscurrent_job = [
                    '' => 'Select..',
                    1 => 'Yes', 
                    0 => 'No'
                ];
    
    $titles = [
            '' => 'Title', 
            'Mr' => 'Mr',
            'Ms' => 'Ms', 
            'Mrs' => 'Mrs'
        ];
    
    $has_criminalrecord = [
                    1 => 'Yes',
                    0 => 'No'
                ];
    
    $is_repeat_applicant = [
                    1 => 'Yes',
                    0 => 'No'
                ];
    
    $is_organisational_member = [
                    1 => 'Yes',
                    0 => 'No'
                ];
    
    /* @var $this yii\web\View */
    $this->title = 'Applicant Profile';
?>

    <div class="site-index">
        <div class = "custom_wrapper" style="min-height:4800px;">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                    <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                    <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body" style="min-height:4500px;">                
                <h1 class="custom_h1"><?=$applicant->title . ". " . $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></h1>
                
                <?php if ($offers == true && $applicant->hasdeferred == 0 /*&& ($applicant->hasduplicate == 0*/):?>
                    <a class="btn btn-warning glyphicon glyphicon glyphicon-share-alt pull-right" style="margin-right:2.5%" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/defer-applicant', 'personid' => $applicant->personid, 'applicantid' => $applicant->applicantid]);?> role="button"> Defer Applicant</a>
                <?php elseif ($applicant->hasdeferred == 1):?>
                    
                    <div class ="btn btn-danger" style="font-size:16px; width: 95%; margin-left: 2.5%;">
                        Applicant has been issued an offer but has subsequently deferred their enrollment.
                    </div>
                    <table class="table table-striped"  style="font-size:16px; width: 95%; margin-left: 2.5%;">
                            <thead>
                                <tr>
                                    <th>Officer</th>
                                    <th>Date</th>
                                    <th>Deferral Details</th>
                                    
                                    <?php if ($applicant_deferral->dateresumed == NULL && $applicant_deferral->resumedby == NULL):?>
                                        <th>Cancel</th>
                                        <th>Resume</th>
                                    <?php endif;?>
                                        
                                    <?php if ($applicant_deferral->dateresumed != NULL && $applicant_deferral->resumedby != NULL):?>
                                        <th>Resumed By</th>
                                        <th>Date Resumed</th>
                                    <?php endif;?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= Employee::getEmployeeName($applicant_deferral->deferredby) ;?></td>
                                    <td><?= $applicant_deferral->deferraldate ;?></td>
                                    <td><?= $applicant_deferral->details ;?></td>
                                    
                                    <?php if ($applicant_deferral->dateresumed == NULL && $applicant_deferral->resumedby == NULL):?>
                                        <td><a class="btn btn-danger glyphicon glyphicon-remove-sign" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/cancel-deferral', 'personid' => $applicant->personid, 'applicantid' => $applicant->applicantid]);?> role="button"> Cancel </a></td>
                                        <td><a class="btn btn-primary glyphicon glyphicon-remove-sign"  href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/resume-deferral', 'personid' => $applicant->personid, 'applicantid' => $applicant->applicantid]);?> role="button"> Resume</a></td>
                                    <?php endif;?>
                                        
                                    <?php if ($applicant_deferral->dateresumed != NULL && $applicant_deferral->resumedby != NULL):?>
                                        <td><?= $applicant_deferral->resumedby ;?></td>
                                        <td><?= $applicant_deferral->dateresumed ;?></td>
                                    <?php endif;?>
                                </tr>
                            </tbody>
                        </table>
                    <br/>
               <?php endif;?>
                    
               <?php if ($applicant->hasduplicate == 1):?>
                    <div class ="btn btn-danger" style="font-size:16px; width: 95%; margin-left: 2.5%;">
                        Applicant has duplicate applications related to the same/related application periods
                    </div>
                    <br/><br/>
                    
                     <?php if ($applicant->isprimary == 1):?>
                        <div class ="btn btn-warning" style="font-size:16px; width: 95%; margin-left: 2.5%;">
                           This account has been identified as the primary applicant account.
                       </div><br/><br/>
                    <?php else:?>
                        <div class ="btn btn-warning" style="font-size:16px; width: 95%; margin-left: 2.5%;">
                           This account has been identified as the duplicate applicant account.
                       </div><br/><br/>
                    <?php endif;?>
               <?php endif;?>
                
                
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="font-size:16px; width: 95%; margin-left: 2.5%;">
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewProfileTab')):?>
                            <li role="presentation" class="active"><a href="#personal_information" aria-controls="personal_information" role="tab" data-toggle="tab">Profile</a></li>
                        <?php endif;?>    
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewMedicalDetailsTab')):?>
                            <li role="presentation"><a href="#medical_details" aria-controls="medical_details" role="tab" data-toggle="tab">Medical Details</a></li>
                        <?php endif;?>     
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewAdditionalDetailsTab')):?>
                            <li role="presentation"><a href="#additional_details" aria-controls="additional_details" role="tab" data-toggle="tab">Additional Details</a></li>
                        <?php endif;?>     
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewInstitutionsTab')):?>    
                            <li role="presentation"><a href="#academic_history" aria-controls="academic_history" role="tab" data-toggle="tab">Institutions</a></li>
                        <?php endif;?>     
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewAcademicQualificationsTab')):?>    
                            <li role="presentation"><a href="#qualifications" aria-controls="qualifications" role="tab" data-toggle="tab">Academic Qualifications</a></li>
                        <?php endif;?>     
                        <?php if(Yii::$app->user->can('ViewAllTabs') || Yii::$app->user->can('viewApplicationOffersTab')):?>    
                            <li role="presentation"><a href="#applications" aria-controls="applications" role="tab" data-toggle="tab">Applications & Offers</a></li>                           
                        <?php endif;?>
                    </ul><br/>
                    
                    <?php if ($applicant->hasduplicate == 0):?>
                        <p class="general_text" style="margin-left:2.5%">
                            Would you like to flag this applicant as a duplicate?
                            <?= Html::radioList('flag_status', null, ["Yes" => "Yes", "No" => "No"], ['style' => 'margin-left:2.5%', 'class'=> 'form_field', 'onclick'=> 'toggleFlagControls();']);?>
                        </p>
                        
                        <div id="flag-controls" style="display:none">
                            <?php 
                                $form = ActiveForm::begin([
                                    'action' => Url::to(['view-applicant/update-duplicate-status', 'applicantusername' => $applicantusername, 'unrestricted' => 1, 'personid' => $applicant->personid, 'applicantid' => $applicant->applicantid, 'flag' => 1]),
                                    'id' => 'flag-duplicate',
                                    'options' => [
                                        'style' => 'margin-left:2.5%',
                                       ]]);
                             ?>
                                <?= Html::label( 'Enter StudentID for related applicant:  ',  'studentid_label'); ?>
                                <?= Html::input('text', 'studentid'); ?>
                                <?= Html::submitButton('Update', ['class' => 'btn btn-md btn-success', 'style' => 'margin-left:2.5%;']) ?>
                            <?php ActiveForm::end();?>
                        </div>
                         
                     <?php else:?>
<!--                        <p class="general_text" style="margin-left:2.5%">
                            Would you like to remove a duplicate flag?
                            <?= Html::radioList('flag_status', null, ["Yes" => "Yes", "No" => "No"], ['style' => 'margin-left:2.5%', 'class'=> 'form_field', 'onclick'=> 'toggleFlagControls();']);?>
                        </p>
                        
                        <div id="flag-controls" style="display:none">
                            <?php 
                                $form = ActiveForm::begin([
                                    'action' => Url::to(['view-applicant/update-duplicate-status', 'applicantusername' => $applicantusername, 'unrestricted' => 1, 'personid' => $applicant->personid, 'applicantid' => $applicant->applicantid, 'flag' => 0]),
                                    'id' => 'flag-duplicate',
                                    'options' => [
                                        'style' => 'margin-left:2.5%',
                                       ]]);
                             ?>
                                <?= Html::label( 'Enter StudentID for related applicant:  ',  'studentid_label'); ?>
                                <?= Html::input('text', 'studentid'); ?>
                                <?= Html::submitButton('Update', ['class' => 'btn btn-md btn-success', 'style' => 'margin-left:2.5%;']) ?>
                            <?php ActiveForm::end();?>
                        </div>-->
                         
                        <div class='dropdown pull-right' style='margin-right:2.5%'>
                            <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                 <strong>Select Duplicate Applications...</strong>
                                <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                <?php
                                    $linked_records = ConcurrentApplicant::getAssociatedApplicants($applicant->applicantid);
                                    $unique_applicant_ids = array();
                                    foreach ($linked_records as $record)
                                    {
                                        if ($record->primaryapplicantid == $applicant->applicantid  
                                                && in_array($record->secondaryapplicantid, $unique_applicant_ids) == false
                                             )
                                         {
                                             $unique_applicant_ids[] = $record->secondaryapplicantid;
                                         }
                                        
                                         elseif ($record->secondaryapplicantid == $applicant->applicantid  
                                                    && in_array($record->primaryapplicantid, $unique_applicant_ids) == false
                                                 )  
                                         {
                                             $unique_applicant_ids[] = $record->primaryapplicantid;
                                         }
                                    }
                                    
                                    foreach ($unique_applicant_ids as $id)
                                    {
                                        $app_record = Applicant::find()
                                                ->where(['applicantid' => $id, 'isdeleted' => 0])
                                                ->one();
                                        if ($app_record)
                                        {
                                            $user_record = User::find()
                                                    ->where(['personid' => $app_record->personid, 'isdeleted' => 0])
                                                ->one();
                                            if ($user_record)
                                            {
                                                $name = $app_record->title . ". " . $app_record->firstname . " " . $app_record->lastname;
                                                $label = $user_record->username . " - " . $name;
                                                $hyperlink = Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile/', 
                                                                            'applicantusername' => $user_record->username,
                                                                            'unrestricted' => 1
                                                                         ]);
                                                echo "<li><a href='$hyperlink' target='_blank'>$label</a></li>";
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div><br/>
                     <?php endif;?>
                   
                      
                    <!--Not sure why this is here [18/10/2016]-->
                    <?php if(Application::getAllVerifiedApplications($applicant->personid) == false):?>  
                        <div style="width:95%; margin: 0 auto;">
                            <br/><?=Html::a(' Reset Application', 
                                ['view-applicant/reset-applications', 'personid' => $applicant->personid], 
                                ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to reset this application?',
                                        'method' => 'post',
                                    ],
                                ]);?><br/>
                        </div>
                    <?php endif;?>
                                    
                                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="personal_information"> 
                            <br/>
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                            <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewGeneral')):?>    
                                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">General
                                <?php if(Yii::$app->user->can('editGeneral')):?>        
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-general', 'personid' => $applicant->personid]);?> role="button"> Edit</a>                                    
                                <?php endif;?>
                                </div>

                                <!-- Table -->
                                <table class="table table-hover" style="margin: 0 auto;">
                                    <tr>
                                        <td rowspan="3"> 
                                            <?php if($applicant->photopath == NULL || strcmp($applicant->photopath, "") ==0 ): ?>
                                                <?php if (strcasecmp($applicant->gender, "male") == 0): ?>
                                                    <img src="<?=Url::to('css/dist/img/avatar_male(150_150).png');?>" alt="avatar_male" class="img-rounded">
                                                <?php elseif (strcasecmp($applicant->gender, "female") == 0): ?>
                                                    <img src="<?=Url::to('css/dist/img/avatar_female(150_150).png');?>" alt="avatar_female" class="img-rounded">
                                                <?php endif;?>
                                            <?php else: ?>
                                                    <img src="<?=$applicant->photopath;?>" alt="student_picture" class="img-rounded">
                                            <?php endif;?>
                                        </td>
                                        <th>Student ID</th>
                                        <td><?=$user->username;?></td>
                                        <th>Applicant Status</th>
                                        <td><?=$applicant_status?></td>
                                    </tr>

                                    <tr>
                                        <th>Full Name</th>
                                        <td><?=$applicant->title . ". " . $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></td>
                                        <th>Gender</th>
                                        <td><?=$applicant->gender;?></td>
                                    </tr>

                                    <tr>
                                        <th>Date Of Birth</th>
                                        <td><?=$applicant->dateofbirth;?></td>
                                        <th>Marital Status</th>
                                        <td><?=$applicant->maritalstatus;?></td>                                  
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <th>Nationality</th>
                                        <td><?=$applicant->nationality;?></td>
                                        <th>Place Of Birth</th>
                                        <td><?=$applicant->placeofbirth;?></td>                                  
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <th>Religion</th>
                                        <td><?=$applicant->religion;?></td> 
                                        <th>Sponsor's Name</th>
                                        <?php if($applicant->sponsorname == NULL || strcmp($applicant->sponsorname,"")==0):?>
                                            <td>--</td>
                                        <?php else:?>
                                            <td><?=$applicant->sponsorname;?></td>  
                                        <?php endif;?>
                                    </tr>
                                </table>
                            <?php endif;?>   

                            <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewContactDetails')):?>     
                                 <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Contact Details 
                                <?php if(Yii::$app->user->can('editContactDetails')):?>
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-contact-details', 'personid' => $applicant->personid]);?> role="button"> Edit</a>
                                <?php endif;?>
                                </div>
                                <!-- Table -->
                                <table class="table table-hover" style="margin: 0 auto;">
                                    <tr>
                                        <td></td>
                                        <th>Home Phone</th>
                                        <?php if($phone->homephone==NULL  ||  strcmp($phone->homephone, ' ')==0 || strcmp($phone->homephone, 'none')==0 || strcmp($phone->homephone, 'nil')==0 || strcmp($phone->homephone, '-')==0 ):?>
                                             <td>--</td>
                                        <?php else:?>
                                            <td><?=$phone->homephone;?></td> 
                                        <?php endif;?>
                                        <th>Cell Phone</th>
                                        <?php if($phone->cellphone==NULL  ||  strcmp($phone->cellphone, ' ')==0 || strcmp($phone->cellphone, 'none')==0 || strcmp($phone->cellphone, 'nil')==0 || strcmp($phone->cellphone, '-')==0 ):?>
                                             <td>--</td>
                                        <?php else:?>
                                            <td><?=$phone->cellphone;?></td> 
                                        <?php endif;?>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <th>Work Phone</th>
                                        <?php if($phone->workphone==NULL  ||  strcmp($phone->workphone, ' ')==0 || strcmp($phone->workphone, 'none')==0 || strcmp($phone->workphone, 'nil')==0 || strcmp($phone->workphone, '-')==0 ):?>
                                             <td>--</td>
                                        <?php else:?>
                                            <td><?=$phone->workphone;?></td> 
                                        <?php endif;?>
                                        <th>Personal Email</th>
                                        <td><?=$email->email;?></td>   
                                    </tr>
                                </table>
                            <?php endif;?>    

                            <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewAddresses')):?>     
                                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Addresses
                                <?php if(Yii::$app->user->can('editAddresses')):?>
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-addresses', 'personid' => $applicant->personid]);?> role="button"> Edit</a>
                                <?php endif;?>
                                </div>
                                <!-- Table -->
                                <table class="table table-hover" style="margin: 0 auto;">
                                    <tr>
                                        <th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Permanent Address</th>
                                        <th>Country</th>
                                        <td><?=$permanentaddress->country;?></td> 
                                        <th>Town</th>
                                        <?php if($permanentaddress->town == NULL || strcmp($permanentaddress->town,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$permanentaddress->town;?></td>  
                                        <?php endif; ?>                        
                                    </tr>
                                    <tr>
                                        <!--<td></td>-->
                                        <th>Address Line</th>
                                        <?php if($permanentaddress->addressline == NULL || strcmp($permanentaddress->addressline,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$permanentaddress->addressline;?></td>  
                                        <?php endif; ?>                                           
                                    </tr>                                      

                                    <tr>
                                        <th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Residential Address</th>
                                        <th>Country</th>
                                        <td><?=$residentaladdress->country;?></td> 
                                        <th>Town</th>
                                        <?php if($residentaladdress->town == NULL || strcmp($residentaladdress->town,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$residentaladdress->town;?></td>
                                        <?php endif; ?>                                              
                                    </tr>
                                    <tr>
                                        <!--<td></td>-->
                                        <th>Address Line</th>
                                        <?php if($residentaladdress->addressline == NULL || strcmp($residentaladdress->addressline,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$residentaladdress->addressline;?></td>
                                        <?php endif; ?>                                             
                                    </tr>

                                    <tr>
                                        <th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>Postal Address</th>
                                        <th>Country</th>
                                        <td><?=$postaladdress->country;?></td> 
                                        <th>Town</th>
                                        <?php if($postaladdress->town == NULL || strcmp($postaladdress->town,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$postaladdress->town;?></td>
                                        <?php endif; ?>                                              
                                    </tr>
                                    <tr>
                                        <!--<td></td>-->
                                        <th>Address Line</th>
                                        <?php if($postaladdress->addressline == NULL || strcmp($postaladdress->addressline,"") == 0): ?>
                                            <td>--</td>
                                        <?php else: ?>
                                            <td><?=$postaladdress->addressline;?></td>
                                        <?php endif; ?>                                    
                                    </tr>                          
                                </table>
                            <?php endif;?>

                            <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewRelatives')):?>     
                                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Relatives
                                <?php if(Yii::$app->user->can('addRelative')):?>
                                    <a class="btn btn-success glyphicon glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-optional-relative', 'personid' => $applicant->personid]);?> role="button"> Add</a>
                                <?php endif;?>
                                </div>

                                <!-- Table -->
                                <table class="table table-hover" style="margin: 0 auto;">
                                    <?php if ($old_beneficiary!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:top; text-align:center; font-size:1.2em;">Beneficiary
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?>
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $old_beneficiary->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?> 
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$old_beneficiary->title . ". " . $old_beneficiary->firstname . " " . $old_beneficiary->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($old_beneficiary->occupation == NULL || strcmp($old_beneficiary->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_beneficiary->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($old_beneficiary->homephone == NULL || strcmp($old_beneficiary->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_beneficiary->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($old_beneficiary->cellphone == NULL || strcmp($old_beneficiary->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_beneficiary->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($old_beneficiary->workphone == NULL || strcmp($old_beneficiary->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_beneficiary->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($old_beneficiary->email == NULL || strcmp($old_beneficiary->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_beneficiary->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($old_beneficiary->address != NULL && strcmp($old_beneficiary->address,"")!=0):?>
                                                <td><?= $old_beneficiary->address;?></td>                                              
                                            <?php elseif($old_beneficiary->town == NULL || strcmp($old_beneficiary->town,"") == 0  || strcmp($old_beneficiary->town,"other") == 0  || strcmp($old_beneficiary->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$old_beneficiary->addressline . "," . "<br/>" . $old_beneficiary->country ;?></td>
                                            <?php elseif($old_beneficiary->addressline == NULL || strcmp($old_beneficiary->addressline,"") == 0): ?>
                                                <td><?=$old_beneficiary->town . "," .  "<br/>" . $old_beneficiary->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>

                                    <?php if ($new_beneficiary!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">Beneficiary
                                                <?php if(Yii::$app->user->can('editRelatives')):?>    
                                                    <div style="margin-top:40px">
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-compulsory-relative', 'personid' => $applicant->personid, 'recordid' => $new_beneficiary->compulsoryrelationid]);?> role="button"> Edit</a>
                                                    </div>
                                                <?php endif;?>    
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$new_beneficiary->title . ". " . $new_beneficiary->firstname . " " . $new_beneficiary->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($new_beneficiary->occupation == NULL || strcmp($new_beneficiary->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_beneficiary->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($new_beneficiary->homephone == NULL || strcmp($new_beneficiary->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_beneficiary->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($new_beneficiary->cellphone == NULL || strcmp($new_beneficiary->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_beneficiary->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($new_beneficiary->workphone == NULL || strcmp($new_beneficiary->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_beneficiary->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($new_beneficiary->email == NULL || strcmp($new_beneficiary->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_beneficiary->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?=$new_beneficiary->address?></td>
                                            <th>Relation</th>
                                            <td><?=$new_beneficiary->relationdetail?></td>
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($old_emergencycontact!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">Emergency Contact
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?>
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $old_emergencycontact->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$old_emergencycontact->title . ". " . $old_emergencycontact->firstname . " " . $old_emergencycontact->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($old_emergencycontact->occupation == NULL || strcmp($old_emergencycontact->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_emergencycontact->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($old_emergencycontact->homephone == NULL || strcmp($old_emergencycontact->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_emergencycontact->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($old_emergencycontact->cellphone == NULL || strcmp($old_emergencycontact->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_emergencycontact->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($old_emergencycontact->workphone == NULL || strcmp($old_emergencycontact->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_emergencycontact->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($old_emergencycontact->email == NULL || strcmp($old_emergencycontact->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$old_emergencycontact->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($old_emergencycontact->address != NULL && strcmp($old_emergencycontact->address,"")!=0):?>
                                                <td><?= $old_emergencycontact->address;?></td>
                                            <?php elseif($old_emergencycontact->town == NULL || strcmp($old_emergencycontact->town,"") == 0  || strcmp($old_emergencycontact->town,"other") == 0  || strcmp($old_emergencycontact->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$old_emergencycontact->addressline . "," . "<br/>" . $old_emergencycontact->country ;?></td>
                                            <?php elseif($old_emergencycontact->addressline == NULL || strcmp($old_emergencycontact->addressline,"") == 0): ?>
                                                <td><?=$old_emergencycontact->town . "," .  "<br/>" . $old_emergencycontact->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>

                                    <?php if ($new_emergencycontact!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">Emergency Contact
                                                <?php if(Yii::$app->user->can('editRelatives')):?>
                                                    <div style="margin-top:40px">
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-compulsory-relative', 'personid' => $applicant->personid, 'recordid' => $new_emergencycontact->compulsoryrelationid]);?> role="button"> Edit</a>
                                                    </div>
                                                <?php endif;?>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$new_emergencycontact->title . ". " . $new_emergencycontact->firstname . " " . $new_emergencycontact->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($new_emergencycontact->occupation == NULL || strcmp($new_emergencycontact->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_emergencycontact->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($new_emergencycontact->homephone == NULL || strcmp($new_emergencycontact->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_emergencycontact->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($new_emergencycontact->cellphone == NULL || strcmp($new_emergencycontact->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_emergencycontact->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($new_emergencycontact->workphone == NULL || strcmp($new_emergencycontact->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_emergencycontact->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($new_emergencycontact->email == NULL || strcmp($new_emergencycontact->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$new_emergencycontact->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?=$new_emergencycontact->address?></td>
                                            <th>Relation</th>
                                            <td><?=$new_emergencycontact->relationdetail?></td>
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($spouse!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">Spouse
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?>    
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $spouse->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>    
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['view-applicant/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $spouse->relationid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]);?>
                                                        </div>
                                                    <?php endif?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$spouse->title . ". " . $spouse->firstname . " " . $spouse->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($spouse->occupation == NULL || strcmp($spouse->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$spouse->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($spouse->homephone == NULL || strcmp($spouse->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$spouse->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($spouse->cellphone == NULL || strcmp($spouse->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$spouse->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($spouse->workphone == NULL || strcmp($spouse->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$spouse->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($spouse->email == NULL || strcmp($spouse->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$spouse->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($spouse->town == NULL || strcmp($spouse->town,"") == 0  || strcmp($spouse->town,"other") == 0  || strcmp($spouse->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$spouse->addressline . "," . "<br/>" . $spouse->country ;?></td>
                                            <?php elseif($spouse->addressline == NULL || strcmp($spouse->addressline,"") == 0): ?>
                                                <td><?=$spouse->town . "," .  "<br/>" . $spouse->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($mother!= false):?>
                                        <tr>
                                            <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">Mother
                                                <div style="margin-top:20px">
                                                <?php if(Yii::$app->user->can('editRelatives')):?> 
                                                    <div >
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $mother->relationid]);?> role="button"> Edit</a>
                                                    </div>
                                                <?php endif;?>
                                                <?php if(Yii::$app->user->can('deleteRelative')):?> 
                                                    <div style="margin-top:10px">
                                                        <?=Html::a(' Delete', 
                                                                    ['view-applicant/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $mother->relationid], 
                                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                        'data' => [
                                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                                            'method' => 'post',
                                                                        ],
                                                                    ]);?>
                                                    </div>
                                                <?php endif;?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$mother->title . ". " . $mother->firstname . " " . $mother->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($mother->occupation == NULL || strcmp($mother->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$mother->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($mother->homephone == NULL || strcmp($mother->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$mother->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($mother->cellphone == NULL || strcmp($mother->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$mother->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($mother->workphone == NULL || strcmp($mother->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$mother->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($mother->email == NULL || strcmp($mother->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$mother->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($mother->address != NULL && strcmp($mother->address,"")!=0):?>
                                                <td><?= $mother->address;?></td>
                                            <?php elseif($mother->town == NULL || strcmp($mother->town,"") == 0  || strcmp($mother->town,"other") == 0  || strcmp($mother->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$mother->addressline . "," . "<br/>" . $mother->country ;?></td>
                                            <?php elseif($mother->addressline == NULL || strcmp($mother->addressline,"") == 0): ?>
                                                <td><?=$mother->town . "," .  "<br/>" . $mother->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($father!= false):?>
                                        <tr>
                                            <th rowspan="4"  style="vertical-align:middle; text-align:center; font-size:1.2em;">Father
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?> 
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $father->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>  
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>     
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['view-applicant/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $father->relationid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]);?>
                                                        </div>
                                                    <?php endif;?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$father->title . ". " . $father->firstname . " " . $father->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($father->occupation == NULL || strcmp($father->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$father->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($father->homephone == NULL || strcmp($father->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$father->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($father->cellphone == NULL || strcmp($father->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$father->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($father->workphone == NULL || strcmp($father->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$father->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($father->email == NULL || strcmp($father->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$father->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($father->address != NULL && strcmp($father->address,"")!=0):?>
                                                <td><?= $father->address;?></td>
                                            <?php elseif($father->town == NULL || strcmp($father->town,"") == 0  || strcmp($father->town,"other") == 0  || strcmp($father->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$father->addressline . "," . "<br/>" . $father->country ;?></td>
                                            <?php elseif($father->addressline == NULL || strcmp($father->addressline,"") == 0): ?>
                                                <td><?=$father->town . "," .  "<br/>" . $father->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($nextofkin!= false):?>
                                        <tr>
                                            <th rowspan="4"  style="vertical-align:middle; text-align:center; font-size:1.2em;">Next of Kin
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?> 
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $nextofkin->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?> 
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['view-applicant/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $nextofkin->relationid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]);?>
                                                        </div>
                                                    <?php endif;?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$nextofkin->title . ". " . $nextofkin->firstname . " " . $nextofkin->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($nextofkin->occupation == NULL || strcmp($nextofkin->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$nextofkin->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($nextofkin->homephone == NULL || strcmp($nextofkin->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$nextofkin->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($nextofkin->cellphone == NULL || strcmp($nextofkin->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$nextofkin->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($nextofkin->workphone == NULL || strcmp($nextofkin->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$nextofkin->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($nextofkin->email == NULL || strcmp($nextofkin->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$nextofkin->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($nextofkin->address != NULL && strcmp($nextofkin->address,"")!=0):?>
                                                <td><?= $nextofkin->address;?></td>
                                            <?php elseif($nextofkin->town == NULL || strcmp($nextofkin->town,"") == 0  || strcmp($nextofkin->town,"other") == 0  || strcmp($nextofkin->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$nextofkin->addressline . "," . "<br/>" . $nextofkin->country ;?></td>
                                            <?php elseif($nextofkin->addressline == NULL || strcmp($nextofkin->addressline,"") == 0): ?>
                                                <td><?=$nextofkin->town . "," .  "<br/>" . $nextofkin->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>



                                    <?php if ($guardian!= false):?>
                                        <tr>
                                            <th rowspan="4"  style="vertical-align:middle; text-align:center; font-size:1.2em;">Guardian
                                                <div style="margin-top:20px">
                                                    <?php if(Yii::$app->user->can('editRelatives')):?> 
                                                        <div >
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $guardian->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>     
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['view-applicant/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $guardian->relationid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]);?>
                                                        </div>
                                                    <?php endif;?>
                                                </div>
                                            </th>
                                            <th>Full Name</th>
                                            <td><?=$guardian->title . ". " . $guardian->firstname . " " . $guardian->lastname;?></td>
                                            <th>Occupation</th>
                                            <?php if($guardian->occupation == NULL || strcmp($guardian->occupation,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$guardian->occupation?></td>
                                            <?php endif; ?>    
                                        </tr>
                                        <tr>
                                            <th>Home Phone</th>
                                            <?php if($guardian->homephone == NULL || strcmp($guardian->homephone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$guardian->homephone?></td>
                                            <?php endif; ?>    
                                            <th>Cell Phone</th>
                                            <?php if($guardian->cellphone == NULL || strcmp($guardian->cellphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$guardian->cellphone?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Work Phone</th>
                                            <?php if($guardian->workphone == NULL || strcmp($guardian->workphone,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$guardian->workphone?></td>
                                            <?php endif; ?> 
                                            <th>Email</th>
                                            <?php if($guardian->email == NULL || strcmp($guardian->email,"") == 0): ?>
                                                <td>--</td>
                                            <?php else: ?>
                                                <td><?=$guardian->email?></td>
                                            <?php endif; ?> 
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <?php if($guardian->address != NULL && strcmp($guardian->address,"")!=0):?>
                                                <td><?= $guardian->address;?></td>
                                            <?php elseif($guardian->town == NULL || strcmp($guardian->town,"") == 0  || strcmp($guardian->town,"other") == 0  || strcmp($guardian->country,"st. vincent and the grenadines") != 0): ?>
                                                <td><?=$guardian->addressline . "," . "<br/>" . $guardian->country ;?></td>
                                            <?php elseif($guardian->addressline == NULL || strcmp($guardian->addressline,"") == 0): ?>
                                                <td><?=$guardian->town . "," .  "<br/>" . $guardian->country ;?></td>
                                            <?php endif; ?> 
                                        </tr>
                                    <?php endif;?>
                                </table>
                            <?php endif;?> 
                            </div>
                            
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewGeneral')):?>    
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Extracurricular Activities
                                    <?php if(Yii::$app->user->can('editGeneral')):?>        
                                        <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-extracurricular', 'personid' => $applicant->personid]);?> role="button"> Edit</a>                                    
                                    <?php endif;?>
                                    </div>

                                    <!-- Table -->
                                    <table class="table table-hover" style="margin: 0 auto;">
                                        <tr>
                                            <th style='width:30%'>National Sports</th>
                                            <?php if ($applicant->nationalsports == NULL || $applicant->nationalsports==" "):?>
                                                <td  style='width:70%'>Applicant has never been a national athlete representative</td>
                                            <?php else:?>
                                                <td style='width:70%'><?=$applicant->nationalsports?></td>
                                            <?php endif;?>
                                        </tr> 
                                        <tr>
                                            <th style='width:30%'>Recreational Sports</th>
                                            <?php if ($applicant->othersports == NULL || $applicant->othersports==" "):?>
                                                <td  style='width:70%'>Applicant does not play any sports recreationally</td>
                                            <?php else:?>
                                                <td style='width:70%'><?=$applicant->othersports?></td>
                                            <?php endif;?>
                                        </tr>
                                        <tr>
                                            <th style='width:30%'>Club Memberships</th>
                                            <?php if ($applicant->clubs == NULL || $applicant->clubs==" "):?>
                                                <td  style='width:70%'>Applicant is not a member of any clubs</td>
                                            <?php else:?>
                                                <td style='width:70%'><?=$applicant->clubs?></td>
                                            <?php endif;?>
                                        </tr>
                                        <tr>
                                            <th style='width:30%'>Other Interests</th>
                                            <?php if ($applicant->otherinterests == NULL || $applicant->otherinterests==" "):?>
                                                <td  style='width:70%'>Applicant has not indicated any other extracurricular interests/activities</td>
                                            <?php else:?>
                                                <td style='width:70%'><?=$applicant->otherinterests?></td>
                                            <?php endif;?>
                                        </tr>
                                    </table>
                                <?php endif;?>
                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="medical_details">                              
                            </br>                              
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewMedicalDetailsData') || Yii::$app->user->can('viewMedicalCondition')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Medical Conditions
                                        <?php if(Yii::$app->user->can('addMedicalCondition')):?>   
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-medical-condition', 'personid' => $applicant->personid]);?> role="button"> Add</a>
                                        <?php endif;?>
                                    </div>
                                    <?php 

                                        if($medicalConditions == false)
                                        {
                                            echo "<h3>Student has not indicated any medical conditions</h3>";
                                        }
                                        else
                                        {
                                            //Table
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                foreach ($medicalConditions as $medicalCondition) 
                                                {
                                                    $delete_hyperlink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid]);
                                                    $edit_hyperlink = Url::toRoute(['/subcomponents/admissions/view-applicant/edit-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid]);

                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:top; text-align:center; font-size:1.2em;'>$medicalCondition->medicalcondition";
                                                            echo "<div style='margin-top:20px'>";
                                                                if(Yii::$app->user->can('deleteMedicalCondition'))
                                                                {
                                                                    echo Html::a(' Delete', 
                                                                                        ['view-applicant/delete-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid], 
                                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                                            'data' => [
                                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                                'method' => 'post',
                                                                                            ],
                                                                                            'style' => 'margin-right:20px',
                                                                                        ]);
                                                                }
                                                                if(Yii::$app->user->can('editMedicalCondition'))
                                                                {
                                                                    echo "<a class='btn btn-info glyphicon glyphicon-pencil' href='$edit_hyperlink' role='button'> Edit</a>";
                                                                }
                                                            echo "</div>";
                                                        echo "</th>";

                                                        echo "<th>Description</th>";
                                                        if ($medicalCondition->description != NULL || strcmp($medicalCondition->description,"")!=0)
                                                            echo "<td>$medicalCondition->description</td>";
                                                        else
                                                            echo "<td>--</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th>Emergency Action</th>";                                                      
                                                        if ($medicalCondition->emergencyaction != NULL || strcmp($medicalCondition->emergencyaction,"")!=0)
                                                            echo "<td>$medicalCondition->emergencyaction</td>";
                                                        else
                                                            echo "<td>--</td>";
                                                    echo "</tr>";
                                                }
                                            echo "</table>";
                                        }
                                    ?>
                                <?php endif;?>
                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="additional_details">                              
                            </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php 
                                    if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                    {
                                        echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>General Work Experience</h3>";
                                        if ($general_work_experience == false)
                                        {
                                            $val = "Applicant has not indicated that they have prior general work experience";
                                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em; margin:0 auto'>$val</div>";
                                                echo "<table class='table table-hover' style='margin: 0 auto;'>"; 
                                                    echo "<tr>";
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                            $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/general-work-experience', 'personid' => $applicant->personid]);
                                                            echo "<td colspan='5'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Job Role</a></td>";
                                                        }
                                                    echo "</tr>";
                                                echo "</table>"; 
                                        }
//                                        if ($general_work_experience==false)
//                                        {
//                                            echo "</br><p><strong>No work experience information has been entered.</strong></p></br>";
//                                        }
                                        else
                                        {
                                            for($i = 0 ; $i < count($general_work_experience) ; $i++) 
                                            {
                                                $val = $i+1;
                                                $generalworkexperienceid = $general_work_experience[$i]->generalworkexperienceid;
                                                $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/general-work-experience', 'personid' => $applicant->personid, 'recordid' => $generalworkexperienceid]);
                                                $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-general-work-experience', 'personid' => $applicant->personid, 'recordid' => $generalworkexperienceid]);

                                                echo "<div class='panel-heading' style='color:grey; font-weight:bold; font-size:1.3em'>#$val ";
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                        echo Html::a(' Delete', 
                                                                        ['delete-general-work-experience', 'personid' => $applicant->personid, 'recordid' => $generalworkexperienceid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                         'style' => 'margin-left:10px',
                                                                        ]);
                                                    }
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                        echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                    }
                                                echo "</div>";

                                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                    echo "<tr>";
                                                        echo "<th rowspan='3' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$general_work_experience[$i]->role}</th>";
                                                        echo "<th>Employer</th>";
                                                        echo "<td>{$general_work_experience[$i]->employer}</td>";
                                                        echo "<th>Employer Address</th>";
                                                        echo "<td>{$general_work_experience[$i]->employeraddress}</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th>Nature of Duties</th>";
                                                        echo "<td>{$general_work_experience[$i]->natureofduties}</td>";
                                                        echo "<th>Salary</th>";
                                                        echo "<td>{$general_work_experience[$i]->salary}</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th>Start Date</th>";
                                                        echo "<td>{$general_work_experience[$i]->startdate}</td>";
                                                        echo "<th>End Date</th>";
                                                        echo "<td style='height:65px'>{$general_work_experience[$i]->enddate}</td>";
                                                    echo "</tr>";
                                                echo "</table>"; 
                                            }
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                if(Yii::$app->user->can('verifyApplicants'))
                                                {
                                                    $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/general-work-experience', 'personid' => $applicant->personid]);
                                                    echo "<tr>";
                                                        echo "<td></td>";
                                                        echo "<td></td>";
                                                        echo "<td></td>";
                                                        echo "<td></td>";
                                                        echo "<td><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Job Role</a></td>";
                                                    echo "</tr>";
                                                }     
                                            echo "</table>"; 
                                        }
                                    }
                                ?>
                            </div>
                            
                            
                            </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php 
                                    if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                    {
                                        echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>References</h3>";

                                        if ($references==false)
                                        {
                                            echo "</br><p><strong>User has not submitted any references.</strong></p></br>";
                                        }
                                        else
                                        {
                                            for($i = 0 ; $i < count($references) ; $i++) 
                                            {
                                                $val = $i+1;
                                                $referenceid = $references[$i]->referenceid;
                                                $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/edit-reference', 'personid' => $applicant->personid, 'recordid' => $referenceid]);
//                                                $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-reference', 'personid' => $applicant->personid, 'recordid' => $referenceid]);

                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>#$val ";
                                                    if(Yii::$app->user->can('verifyApplicants'))
//                                                    {
//                                                        echo Html::a(' Delete', 
//                                                                        ['delete-reference', 'personid' => $applicant->personid, 'recordid' => $referenceid], 
//                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
//                                                                            'data' => [
//                                                                                'confirm' => 'Are you sure you want to delete this item?',
//                                                                                'method' => 'post',
//                                                                            ],
//                                                                         'style' => 'margin-left:10px',
//                                                                        ]);
//                                                    }
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                            echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                    }
                                                echo "</div>";

                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                        echo "<tr>";
                                                            $fullname = $references[$i]->title . ". " . $references[$i]->firstname . " " . $references[$i]->lastname;
                                                            echo "<th rowspan='3' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$fullname}</th>";
                                                            echo "<th>Address</th>";
                                                            echo "<td>{$references[$i]->address}</td>";
                                                            echo "<th>Occupation</th>";
                                                            echo "<td>{$references[$i]->occupation}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Contact Number</th>";
                                                            echo "<td>{$references[$i]->contactnumber}</td>";
                                                        echo "</tr>";
                                                    echo "</table>";                     
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            
                            
                            <?php if($applicant->applicantintentid==6):?>
                                </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <?php 
                                        if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                        {
                                            echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Nursing Experience</h3>";

                                            if ($nursing==false)
                                            {
                                                $val = "Applicant has not indicated that they have prior nursing experience";
                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em; margin:0 auto'>$val</div>";
                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>"; 
                                                        echo "<tr>";
                                                            if(Yii::$app->user->can('verifyApplicants'))
                                                            {
                                                                $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-work-experience', 'personid' => $applicant->personid]);
                                                                echo "<td colspan='5'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Nursing Role</a></td>";
                                                            }
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                            }
                                            else
                                            {
                                                $val = "";
                                                $nurseworkexperienceid = $nursing->nurseworkexperienceid;
                                                $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-work-experience', 'personid' => $applicant->personid, 'recordid' => $nurseworkexperienceid]);
                                                $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-nurse-work-experience', 'personid' => $applicant->personid, 'recordid' => $nurseworkexperienceid]);

                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Experience Details";
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                        echo Html::a(' Delete', 
                                                                        ['delete-nurse-work-experience', 'personid' => $applicant->personid, 'recordid' => $nurseworkexperienceid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                         'style' => 'margin-left:10px',
                                                                        ]);
                                                    }
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                            echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                    }
                                                echo "</div>";

                                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                    echo "<tr>";
                                                        echo "<th rowspan='5' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$nursing->location}</th>";
                                                        echo "<th>Nature of Duties</th>";
                                                        echo "<td colspan='3'>{$nursing->natureoftraining}</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th>Tenure Period</th>";
                                                        echo "<td colspan='3'>{$nursing->tenureperiod}</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th>Departure Reason (if applicable)</th>";
                                                        echo "<td colspan='3'>{$nursing->departreason}</td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        if(NurseWorkExperience::getNurseWorkExperience($applicant->personid)==false && Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                            $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-work-experience', 'personid' => $applicant->personid]);
                                                            echo "<td colspan='4'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Nursing Role</a></td>";
                                                        }
                                                    echo "</tr>";
                                                echo "</table>"; 
                                            }
                                        }
                                    ?>
                                </div>
                                
                                </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <?php 
                                        if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                        {
                                            echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Nursing Certification</h3>";

                                            if ($nursing_certification==false)
                                            {
                                                $val = "Applicant has not indicated that they have prior nursing cetificates";
                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em; margin:0 auto'>$val</div>";
                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>"; 
                                                        echo "<tr>";
                                                            if(Yii::$app->user->can('verifyApplicants'))
                                                            {
                                                                $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-certification', 'personid' => $applicant->personid]);
                                                                echo "<td colspan='5'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Nursing Role</a></td>";
                                                            }
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                            }
                                            else
                                            {
                                                for($i = 0 ; $i < count($nursing_certification) ; $i++) 
                                                {
                                                    $val = $i+1;
                                                    $nursecertificationid = $nursing_certification[$i]->nursepriorcertificationid;
                                                    $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-certification', 'personid' => $applicant->personid, 'recordid' => $nursecertificationid]);
                                                    $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-nurse-certification', 'personid' => $applicant->personid, 'recordid' => $nursecertificationid]);

                                                    echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>#$val";
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                            echo Html::a(' Delete', 
                                                                            ['delete-nurse-certification', 'personid' => $applicant->personid, 'recordid' => $nursecertificationid], 
                                                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                                'data' => [
                                                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                                                    'method' => 'post',
                                                                                ],
                                                                             'style' => 'margin-left:10px',
                                                                            ]);
                                                        }
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                                echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                        }
                                                    echo "</div>";

                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                        echo "<tr>";
                                                            echo "<th rowspan='3' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$nursing_certification[$i]->certification}</th>";
                                                            echo "<th>Institution</th>";
                                                            echo "<td colspan='3'>{$nursing_certification[$i]->institutionname}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Dates of Training</th>";
                                                            echo "<td colspan='3'>{$nursing_certification[$i]->datesoftraining}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Length of Training</th>";
                                                            echo "<td colspan='3'>{$nursing_certification[$i]->lengthoftraining}</td>";
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                                }
                                                
                                                echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                    if(Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                        $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/nurse-certification', 'personid' => $applicant->personid]);
                                                        echo "<tr>";
                                                            
                                                            echo "<td colspan='3'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Nursing Certificate</a></td>";
                                                        echo "</tr>";
                                                    }     
                                                echo "</table>"; 
                                            }
                                        }
                                    ?>
                                </div>
                                
                                
                                </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <?php if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
                                        <h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Nursing Additional Information</h3>
                                        <?php 
                                            $form = yii\bootstrap\ActiveForm::begin([
                                                'action' => Url::to(['view-applicant/update-nursing-information', 'personid' => $applicant->personid]),
                                                'id' => 'nursing-info-form',
                                                'enableAjaxValidation' => false,
                                                'enableClientValidation' => true,
                                                'validateOnSubmit' => true,
                                                'validateOnBlur' => true,
                                                'successCssClass' => 'alert in alert-block fade alert-success',
                                                'errorCssClass' => 'alert in alert-block fade alert-error',
                                                'options' => [
                                                    'class' => 'form-layout',
                                                    'style' => 'margin-top:30px; margin-bottom:30px; width:95%',
                                                ],
                                                
                                            ])
                                        ?>
                                        
                                            <fieldset >
                                                <legend>Family Information</legend>
                                                <?= $form->field($nursinginfo, 'childcount')->label("How many children do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'childCount', 'onchange'=> 'checkChildCount();']);?>

                                                <?php if (NursingAdditionalInfo::hasChildren($applicant->personid) == true):?>
                                                    <div id="ages">
                                                        <?= $form->field($nursinginfo, 'childages')->label("Ages of children *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'childAges']) ?>
                                                    </div>
                                                <?php else :?>
                                                    <div id="ages" style="display:none">
                                                        <?= $form->field($nursinginfo, 'childages')->label("Ages of children *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'childAges']) ?>
                                                    </div>
                                                <?php endif ;?>

                                                <?= $form->field($nursinginfo, 'brothercount')->label("How many brothers do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'brotherCount']);?>

                                                <?= $form->field($nursinginfo, 'sistercount')->label("How many sisters do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'sisterCount']);?>
                                            </fieldset><br>

                                            <fieldset >
                                                <legend>Work Experience</legend>
                                                <?= $form->field($nursinginfo, 'yearcompletedschool')->label('Year school was completed: *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                                                <?= $form->field($nursinginfo, 'hasworked')->label("Have you worked since leaving school? *", ['class'=> 'form-label'])->inline()->radioList($has_worked, ['id' => 'hasWorked', 'onclick' => 'processOtherApplications();showGeneralWorkExperience();']);?>

                                                <?= $form->field($nursinginfo, 'isworking')->label("Are you currently employed? *", ['class'=> 'form-label'])->inline()->radioList($is_working, ['id' => 'isWorking', 'onclick' => 'processOtherApplications();showGeneralWorkExperience();']);?>

                                                <div id="has-other-applications">
                                                    <?= $form->field($nursinginfo, 'hasotherapplications')->label("Are you currently awaiting any application responses? *", ['class'=> 'form-label'])->inline()->radioList($is_working, ['id' => 'hasOtherApplications', 'onclick' => 'showOtherApplicationDetails();']);?>
                                                </div>

                                                <?php if (NursingAdditionalInfo::hasOtherApplications($applicant->personid) == true):?>
                                                    <div id="other-applications-info">
                                                        <?= $form->field($nursinginfo, 'otherapplicationsinfo')->label("Where have you applied for a job? (Apart from this application? *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'otherApplicationInfo']) ?>
                                                    </div>
                                                <?php else :?>
                                                    <div id="other-applications-info" style="display:none">
                                                        <?= $form->field($nursinginfo, 'otherapplicationsinfo')->label("Where have you applied for a job? (Apart from this application? *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'otherApplicationInfo']) ?>
                                                    </div>
                                                <?php endif ;?>

                                                <?= $form->field($nursinginfo, 'hasnursingexperience')->label("Do you have had any previous nursing or nurse related training? *", ['class'=> 'form-label'])->inline()->radioList($nursing_experience, ['id' => 'nurse-work']);?>
                                            </fieldset></br>

                                            <fieldset >
                                                <legend>Other</legend>
                                                <!-- Is organization member radiolist -->
                                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true):?>
                                                    <?= $form->field($nursinginfo, 'ismember')->label("Are you a member of a professional organisation? *", ['class'=> 'form-label'])->inline()->radioList($is_organisational_member, ['class'=> 'form-field', 'onclick' => 'toggleOrganisationDetails();']);?>
                                                <?php endif;?>

                                                <!-- Organization details -->
                                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::isMember($applicant->personid) == true):?>
                                                    <div id="member-organisations" style="display:block">  
                                                        <?= $form->field($nursinginfo, 'memberorganisations')->label('If yes, state which?', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                                    </div>
                                                <?php else:?>
                                                    <div id="member-organisations" style="display:none">
                                                         <?= $form->field($nursinginfo, 'memberorganisations')->label('If yes, state which?', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>   
                                                    </div>
                                                <?php endif; ?>

                                                <!--Reason for not joining organization-->
                                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::isMember($applicant->personid) == false):?>
                                                    <div id="exclusion-reason" style="display:block">  
                                                        <?= $form->field($nursinginfo, 'exclusionreason')->label('If no, give reason(s)?', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                                    </div>
                                                <?php else:?>
                                                    <div id="exclusion-reason" style="display:none">
                                                         <?= $form->field($nursinginfo, 'exclusionreason')->label('If no, give reason(s)?', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>   
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Is repeat applicant radiolist -->
                                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true):?>
                                                    <?= $form->field($nursinginfo, 'repeatapplicant')->label("Have you applied for entry into this course previously? *", ['class'=> 'form-label'])->inline()->radioList($is_repeat_applicant, ['class'=> 'form-field', 'onclick' => 'togglePreviousYears();']);?>
                                                <?php endif;?>

                                                <!-- Previous years -->
                                                <?php if (Application::hasMidwiferyApplication($applicant->personid) == true  && NursingAdditionalInfo::hasPreviousApplication($applicant->personid) == true):?>
                                                    <div id="previous-years" style="display:block">                                
                                                <?php else:?>
                                                    <div id="previous-years" style="display:none">
                                                <?php endif; ?>
                                                         <?= $form->field($nursinginfo, 'previousyears')->label('If yes, state when?', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>   
                                                    </div>

                                                <?= $form->field($nursinginfo, 'hascriminalrecord')->label("Have your every been charged by the law for any offence? *", ['class'=> 'form-label'])->inline()->radioList($has_criminalrecord);?>

                                                </br><p>State two (2) reasons why you wish to do enroll in your programme of choice.
                                                <?= $form->field($nursinginfo, 'applicationmotivation1')->label("Reason #1 *", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                                                <?= $form->field($nursinginfo, 'applicationmotivation2')->label("Reason #2 *", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>

                                                <?= $form->field($nursinginfo, 'additionalcomments')->label("Other Comments ", ['class'=> 'form-label'])->textArea(['rows' => '5']) ?>
                                            </fieldset></br>

                                            <?php if(Yii::$app->user->can('updateAdditionalDetails')):?>
                                                <div class="form-group">
                                                    <?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
                                                </div>
                                            <?php endif;?>
                                        <?php yii\bootstrap\ActiveForm::end(); ?> 
                                    <?php endif;?>
                                </div>
                            <?php endif;?><!--End of Nursing specific information-->
                            
                            
                            
                            <?php if($applicant->applicantintentid==4):?>
                                </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <?php 
                                        if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                        {
                                            echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Teaching Experience</h3>";
                                            if ($teaching == false)
                                            {
                                                $val = "Applicant has not indicated that they have prior teaching experience";
                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em; margin:0 auto'>$val</div>";
                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>"; 
                                                        echo "<tr>";
                                                            if(Yii::$app->user->can('verifyApplicants'))
                                                            {
                                                                $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/teacher-experience', 'personid' => $applicant->personid]);
                                                                echo "<td colspan='5'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Nursing Role</a></td>";
                                                            }
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                            }
//                                            if ($teaching==false)
//                                            {
//                                                echo "</br><p style='margin: 0 auto;'><strong>No teaching experience information has been entered.</strong></p></br>";
//                                            }
                                            else
                                            {
                                                for($i = 0 ; $i < count($teaching) ; $i++) 
                                                {
                                                    $val = $i+1;
                                                    $teacherexperienceid = $teaching[$i]->teachingexperienceid;
                                                    $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/teacher-experience', 'personid' => $applicant->personid, 'recordid' => $teacherexperienceid]);
                                                    $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-teacher-experience', 'personid' => $applicant->personid, 'recordid' => $teacherexperienceid]);

                                                    echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>#$val ";
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                            echo Html::a(' Delete', 
                                                                            ['delete-teacher-experience', 'personid' => $applicant->personid, 'recordid' => $teacherexperienceid], 
                                                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                                'data' => [
                                                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                                                    'method' => 'post',
                                                                                ],
                                                                             'style' => 'margin-left:10px',
                                                                            ]);
                                                        }
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                                echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                        }
                                                    echo "</div>";

                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                        echo "<tr>";
                                                            echo "<th rowspan='4' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$teaching[$i]->institutionname}</th>";
                                                            echo "<th>Address</th>";
                                                            echo "<td>{$teaching[$i]->address}</td>";
                                                            echo "<th>Start Date</th>";
                                                            echo "<td>{$teaching[$i]->startdate}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Date of Appointment</th>";
                                                            echo "<td>{$teaching[$i]->dateofappointment}</td>";
                                                            echo "<th>End Date</th>";
                                                            echo "<td>{$teaching[$i]->enddate}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Class/Form</th>";
                                                            echo "<td>{$teaching[$i]->classtaught}</td>";
                                                            echo "<th>Subject(s)</th>";
                                                            echo "<td style='height:65px'>{$teaching[$i]->subject}</td>";
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                                }
                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                        echo "<tr>";
                                                            if(Yii::$app->user->can('verifyApplicants'))
                                                            {
                                                                $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/teacher-experience', 'personid' => $applicant->personid]);
                                                                echo "<td colspan='4'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button' > Add Teaching Role</a></td>";
                                                            }
                                                        echo "</tr>";
                                                    echo "</table>"; 
                                            }
                                        }
                                    ?>
                                </div>
                                
                                
                                </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <?php if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
                                        <h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Teaching Additional Information</h3>
                                        <?php 
                                            $form = yii\bootstrap\ActiveForm::begin([
                                                'action' => Url::to(['view-applicant/update-teaching-information', 'personid' => $applicant->personid]),
                                                'id' => 'teaching-info-form',
                                                'enableAjaxValidation' => false,
                                                'enableClientValidation' => true,
                                                'validateOnSubmit' => true,
                                                'validateOnBlur' => true,
                                                'successCssClass' => 'alert in alert-block fade alert-success',
                                                'errorCssClass' => 'alert in alert-block fade alert-error',
                                                'options' => [
                                                    'class' => 'form-layout',
                                                    'style' => 'margin-top:30px; margin-bottom:30px; width:95%',
                                                ],

                                            ])
                                        ?>

                                            <fieldset >
                                                <legend>Family Information</legend>
                                                <?= $form->field($teachinginfo, 'childcount')->label("How many children do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'childCount', 'onchange'=> 'checkChildCount();']);?>

                                                <?php if (TeachingAdditionalInfo::hasChildren($applicant->personid) == true):?>
                                                    <div id="ages">
                                                        <?= $form->field($teachinginfo, 'childages')->label("Ages of children *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'childAges']) ?>
                                                    </div>
                                                <?php else :?>
                                                    <div id="ages" style="display:none">
                                                        <?= $form->field($teachinginfo, 'childages')->label("Ages of children *", ['class'=> 'form-label'])->textArea(['rows' => '1', 'id'=>'childAges']) ?>
                                                    </div>
                                                <?php endif ;?>

                                                <?= $form->field($teachinginfo, 'brothercount')->label("How many brothers do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'brotherCount']);?>

                                                <?= $form->field($teachinginfo, 'sistercount')->label("How many sisters do you have?*", ['class'=> 'form-label'])->dropDownList($relation_count, ['id'=>'sisterCount']);?>
                                            </fieldset></br>

                                            <fieldset >
                                                <legend>Work Experience</legend>
                                                <?= $form->field($teachinginfo, 'yearcompletedschool')->label('Year school was completed: *', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>

                                                <?= $form->field($teachinginfo, 'hasworked')->label("Have you worked since leaving school? *", ['class'=> 'form-label'])->inline()->radioList($has_worked, ['id' => 'hasWorked', 'onclick' => 'showTeacherGeneralWorkExperience();']);?>

                                                <?= $form->field($teachinginfo, 'isworking')->label("Are you currently employed? *", ['class'=> 'form-label'])->inline()->radioList($is_working, ['id' => 'isWorking', 'onclick' => 'showTeacherGeneralWorkExperience();']);?>

                                                <?= $form->field($teachinginfo, 'hasteachingexperience')->label("Do you have had any previous teaching experience? *", ['class'=> 'form-label'])->inline()->radioList($teaching_experience, ['teacher-experience', 'onclick' => 'showTeachingExperience();']);?>
                                            </fieldset></br>

                                            <fieldset >
                                                <legend>Other</legend>              
                                                <?= $form->field($teachinginfo, 'hascriminalrecord')->label("Have your every been charged by the law for any offence? *", ['class'=> 'form-label'])->inline()->radioList($has_criminalrecord);?>

                                                <?= $form->field($teachinginfo, 'applicationmotivation')->label("Why do you want to enroll in this programme? *", ['class'=> 'form-label'])->textArea(['rows' => '15']) ?>

                                                <?= $form->field($teachinginfo, 'additionalcomments')->label("Other Comments ", ['class'=> 'form-label'])->textArea(['rows' => '15']) ?>             
                                            </fieldset></br>

                                            <fieldset >
                                                <legend>Financial Information</legend> 
                                                <?= $form->field($teachinginfo, 'benefactor')->label("How will your studies be financed? *", ['class'=> 'form-label'])->inline()->radioList($financing_options, ['onclick' => 'showBenefactorDetails();']);?>

                                                <div id="benefactor-details" style="display:none;">
                                                    <?= $form->field($teachinginfo, 'benefactordetails')->label('Specify Financer', ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                                </div>

                                                <?= $form->field($teachinginfo, 'appliedforloan')->label("Have you applied for Student Loan? *", ['class'=> 'form-label'])->inline()->radioList($student_loan);?>

                                                <?= $form->field($teachinginfo, 'sponsorship')->label("Have you requested? *", ['class'=> 'form-label'])->inline()->radioList($sponsorship_request, ['onclick' => 'showSponsorNames();']);?>               

                                                <div id="sponsor-names" style="display:none;">
                                                    <?= $form->field($teachinginfo, 'sponsorname')->label("If you are sponsored please state the organization(s).", ['class'=> 'form-label'])->textArea(['rows' => '2']) ?>
                                                </div>
                                            </fieldset></br>

                                            <?php if(Yii::$app->user->can('updateAdditionalDetails')):?>
                                                <div class="form-group">
                                                    <?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
                                                </div>
                                            <?php endif;?>
                                        <?php yii\bootstrap\ActiveForm::end(); ?> 
                                    <?php endif;?>
                                </div>
                            <?php endif;?><!--End of Teaching specific information-->
                            
                            
                            </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php 
                                    if(Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData'))
                                    {
                                        echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Criminal Record</h3>";

                                        if ($criminalrecord==false)
                                        {
                                            $val = "Applicant has not indicated that they have criminal record";
                                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em; margin:0 auto'>$val</div>";
                                                echo "<table class='table table-hover' style='margin: 0 auto;'>"; 
                                                    echo "<tr>";
                                                        if(Yii::$app->user->can('verifyApplicants'))
                                                        {
                                                            $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/criminal-record', 'personid' => $applicant->personid]);
                                                            echo "<td colspan='5'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Criminal Record</a></td>";
                                                        }
                                                    echo "</tr>";
                                                echo "</table>"; 
                                        }
                                        else
                                        {
                                            $val = "";
                                            $criminalrecordid = $criminalrecord->criminalrecordid;
                                            $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/criminal-record', 'personid' => $applicant->personid, 'recordid' => $criminalrecordid]);
                                            $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-criminal-record', 'personid' => $applicant->personid, 'recordid' => $criminalrecordid]);

                                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Details";
                                                if(Yii::$app->user->can('verifyApplicants'))
                                                {
                                                    echo Html::a(' Delete', 
                                                                    ['delete-criminal-record', 'personid' => $applicant->personid, 'recordid' => $criminalrecordid], 
                                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                        'data' => [
                                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                                            'method' => 'post',
                                                                        ],
                                                                     'style' => 'margin-left:10px',
                                                                    ]);
                                                }
                                                if(Yii::$app->user->can('verifyApplicants'))
                                                {
                                                        echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                }
                                            echo "</div>";
                                            
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                echo "<tr>";
//                                                    echo "<th rowspan='3' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$nursing->location}</th>";
                                                    echo "<th>Nature of Charge</th>";
                                                    echo "<td colspan='3'>{$criminalrecord->natureofcharge}</td>";
                                                echo "</tr>";

                                                echo "<tr>";
                                                    echo "<th>Outcome</th>";
                                                    echo "<td colspan='3'>{$criminalrecord->outcome}</td>";
                                                echo "</tr>";

                                                echo "<tr>";
                                                    echo "<th>Date of Conviction (if applicable)</th>";
                                                    echo "<td colspan='3'>{$criminalrecord->dateofconviction}</td>";
                                                echo "</tr>";

                                                echo "<tr>";
                                                   if(CriminalRecord::getCriminalRecord($applicant->personid)==false && Yii::$app->user->can('verifyApplicants'))
                                                    {
                                                        $add_role = Url::toRoute(['/subcomponents/admissions/view-applicant/criminal-record', 'personid' => $applicant->personid]);
                                                        echo "<td colspan='4'><a class='btn btn-success glyphicon glyphicon-plus pull-right' href=$add_role role='button'> Add Criminal Record</a></td>";
                                                    }
                                                echo "</tr>";
                                            echo "</table>"; 
                                        }
                                    }
                                ?>
                            </div>
                        </div><!--End of additional information tab-->
                        
                        


                        <div role="tabpanel" class="tab-pane fade" id="academic_history"> 
                            </br>                              
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewInstitutionsData')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Pre-School Attendance
                                        <?php if(Yii::$app->user->can('addSchool')):?>
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-school', 'personid' => $applicant->personid, 'levelid' => 1]);?> role="button"> Add</a>
                                        <?php endif;?>
                                    </div>
                                    <?php 
                                        if($preschools == false)
                                        {
                                            echo "<h4 style='font-size:1.2em;'>No pre-school records have been entered.</h4>";
                                        }
                                        else
                                        {
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                for($i = 0 ; $i < count($preschools) ; $i++) 
                                                {
                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>$preschoolNames[$i]</th>";
                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>Start Date</th>";
                                                        if ($preschools[$i]->startdate != NULL && strcmp($preschools[$i]->startdate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$preschools[$i]->startdate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";

                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>End Date</th>";                                                      
                                                        if ($preschools[$i]->enddate != NULL && strcmp($preschools[$i]->enddate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$preschools[$i]->enddate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";
                                                        $pre_delete_link = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid]);
                                                        $pre_edit_link =  Url::toRoute(['/subcomponents/admissions/view-applicant/edit-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid, 'levelid' => 1]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                ]);
                                                            echo "</td>";

                                                        }
                                                        if(Yii::$app->user->can('editSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>"
                                                                    . "<a class='btn btn-info glyphicon glyphicon-pencil' href='$pre_edit_link' role='button'> Edit</a>"
                                                                . "</td>";  
                                                        }
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                        echo "<th colspan='3'>Has student graduated from this institution?</th>";
                                                        if ($preschools[$i]->hasgraduated == 1)
                                                            echo "<td colspan='3'>Yes</td>";
                                                        else
                                                            echo "<td colspan='3'>No</td>";
                                                    echo "</tr>";
                                                }
                                            echo "</table>";
                                        }
                                    ?>
                                <?php endif;?>

                                <?php if(Yii::$app->user->can('viewInstitutionsData')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Primary School Attendance
                                        <?php if(Yii::$app->user->can('addSchool')):?>
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-school', 'personid' => $applicant->personid, 'levelid' => 2]);?> role="button"> Add</a>
                                        <?php endif?>
                                    </div>
                                    <?php 
                                        if($primaryschools == false)
                                        {
                                            echo "<h4 style='font-size:1.2em;'>No primary school records have been entered.</h4>";
                                        }
                                        else
                                        {
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                for($i = 0 ; $i < count($primaryschools) ; $i++) 
                                                {
                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em; height:75px'>$primaryschoolNames[$i]</th>";
                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>Start Date</th>";
                                                        if ($primaryschools[$i]->startdate != NULL && strcmp($primaryschools[$i]->startdate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$primaryschools[$i]->startdate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";

                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>End Date</th>";                                                      
                                                        if ($primaryschools[$i]->enddate != NULL && strcmp($primaryschools[$i]->enddate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$primaryschools[$i]->enddate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";
                                                        $pri_delete_link = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid]);
                                                        $pri_edit_link =  Url::toRoute(['/subcomponents/admissions/view-applicant/edit-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid, 'levelid' => 2]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                ]);
                                                            echo "</td>";
                                                        }
                                                        if(Yii::$app->user->can('editSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>"
                                                                    . "<a class='btn btn-info glyphicon glyphicon-pencil' href='$pri_edit_link' role='button'> Edit</a>"
                                                                . "</td>";
                                                        }
                                                    echo "</tr>";   

                                                    echo "<tr>";
                                                        echo "<th colspan='3'>Has student graduated from this institution?</th>";
                                                        if ($primaryschools[$i]->hasgraduated == 1)
                                                            echo "<td colspan='3'>Yes</td>";
                                                        else
                                                            echo "<td colspan='3'>No</td>";
                                                    echo "</tr>";
                                                }
                                            echo "</table>";
                                        }
                                    ?>
                                <?php endif;?>

                                <?php if(Yii::$app->user->can('viewInstitutionsData')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Secondary School Attendance
                                        <?php if(Yii::$app->user->can('addSchool')):?>
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-school', 'personid' => $applicant->personid,  'levelid' => 3]);?> role="button"> Add</a>
                                        <?php endif;?>
                                    </div>
                                    <?php 
                                        if($secondaryschools == false)
                                        {
                                            echo "<h4 style='font-size:1.2em;'>No secondary school records have been entered.</h4>";
                                        }
                                        else
                                        {
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                for($i = 0 ; $i < count($secondaryschools) ; $i++) 
                                                {
                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>$secondaryschoolNames[$i]</th>";
                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>Start Date</th>";
                                                        if ($secondaryschools[$i]->startdate != NULL && strcmp($secondaryschools[$i]->startdate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$secondaryschools[$i]->startdate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";

                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>End Date</th>";                                                      
                                                        if ($secondaryschools[$i]->enddate != NULL && strcmp($secondaryschools[$i]->enddate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$secondaryschools[$i]->enddate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";
                                                        $sec_delete_link = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid]);
                                                        $sec_edit_link =  Url::toRoute(['/subcomponents/admissions/view-applicant/edit-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid, 'levelid' => 3]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                ]);
                                                            echo "</td>";
                                                        }
                                                        if(Yii::$app->user->can('editSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>"
                                                                    . "<a class='btn btn-info glyphicon glyphicon-pencil' href='$sec_edit_link' role='button'> Edit</a>"
                                                                . "</td>";
                                                        }
                                                    echo "</tr>";

                                                     echo "<tr>";
                                                        echo "<th colspan='3'>Has student graduated from this institution?</th>";
                                                        if ($secondaryschools[$i]->hasgraduated == 1)
                                                            echo "<td colspan='3'>Yes</td>";
                                                        else
                                                            echo "<td colspan='3'>No</td>";
                                                    echo "</tr>";
                                                }
                                            echo "</table>";
                                        }
                                    ?>
                                <?php endif;?>

                                <?php if(Yii::$app->user->can('viewInstitutionsData')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Tertiary School Attendance
                                        <?php if(Yii::$app->user->can('addSchool')):?>
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-school', 'personid' => $applicant->personid, 'levelid' => 4]);?> role="button"> Add</a>
                                        <?php endif;?>
                                    </div>
                                    <?php 
                                        if($tertiaryschools == false)
                                        {
                                            echo "<h4 style='font-size:1.2em;'>No tertiary school records have been entered.</h4>";
                                        }
                                        else
                                        {
                                            echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                                for($i = 0 ; $i < count($tertiaryschools) ; $i++) 
                                                {
                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:middle; text-align:center; font-size:1.2em;'>$tertiaryschoolNames[$i]</th>";
                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>Start Date</th>";
                                                        if ($tertiaryschools[$i]->startdate != NULL && strcmp($tertiaryschools[$i]->startdate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$tertiaryschools[$i]->startdate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";

                                                        echo "<th style='vertical-align:middle; text-align:center; height:75px'>End Date</th>";                                                      
                                                        if ($tertiaryschools[$i]->enddate != NULL && strcmp($tertiaryschools[$i]->enddate,"0000-00-00")!=0)
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>{$tertiaryschools[$i]->enddate}</td>";
                                                        else
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>--</td>";
                                                        $ter_delete_link = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid]);
                                                        $ter_edit_link =  Url::toRoute(['/subcomponents/admissions/view-applicant/edit-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid, 'levelid' => 4]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['view-applicant/delete-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                ]);
                                                            echo "</td>";
                                                        }
                                                        if(Yii::$app->user->can('editSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>"    
                                                                . "<a class='btn btn-info glyphicon glyphicon-pencil' href='$ter_edit_link' role='button'> Edit</a>"
                                                            . "</td>";
                                                        }
                                                    echo "</tr>";

                                                     echo "<tr>";
                                                        echo "<th colspan='3'>Has student graduated from this institution?</th>";
                                                        if ($tertiaryschools[$i]->hasgraduated == 1)
                                                            echo "<td colspan='3'>Yes</td>";
                                                        else
                                                            echo "<td colspan='3'>No</td>";
                                                    echo "</tr>";
                                                }
                                            echo "</table>";
                                        }
                                    ?>    
                                <?php endif;?>
                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="qualifications">
                            </br><div class="panel panel-default" style="width:95%; margin: 0 auto;">

                                <?php 
                                    if(Yii::$app->user->can('viewAcademicQualificationsData'))
                                    {
                                        echo "<h3 style='color:green;font-weight:bold; font-size:1.6em; text-align:center'>Qualifications</h3>";

                                        if ($qualifications==false)
                                        {
                                            echo "</br><p><strong>No qualifications have been entered. Please ensure that you make contact with the Registrar to present you appropriate external certificates</strong></p></br>";
                                        }
                                        else
                                        {
                                            for($i = 0 ; $i < count($qualifications) ; $i++) 
                                            {
                                                $val = $i+1;
                                                $qualificationid = $qualifications[$i]->csecqualificationid;
                                                $editlink = Url::toRoute(['/subcomponents/admissions/view-applicant/edit-qualification', 'personid' => $applicant->personid, 'recordid' => $qualificationid]);
                                                $deletelink = Url::toRoute(['/subcomponents/admissions/view-applicant/delete-qualification', 'personid' => $applicant->personid, 'recordid' => $qualificationid]);

                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>#$val ";
                                                    if(Yii::$app->user->can('deleteQualification')  /*&& $applicant_status=="Unverified"*/)
                                                    {
                                                        echo Html::a(' Delete', 
                                                                        ['delete-qualification', 'personid' => $applicant->personid, 'recordid' => $qualificationid], 
                                                                        ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                            'data' => [
                                                                                'confirm' => 'Are you sure you want to delete this item?',
                                                                                'method' => 'post',
                                                                            ],
                                                                         'style' => 'margin-left:10px',
                                                                        ]);
                                                    }
                                                    if(Yii::$app->user->can('editQualification') /*&& $applicant_status=="Unverified"*/)
                                                    {
                                                            echo "<a class='btn btn-info glyphicon glyphicon-pencil pull-right' href=$editlink role='button'> Edit</a>";
                                                    }
                                                echo "</div>";

                                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";                                            
                                                        echo "<tr>";
                                                            echo "<th rowspan='3' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$qualificationDetails[$i]['subject']}</th>";
                                                            echo "<th>Year</th>";
                                                            echo "<td>{$qualifications[$i]->year}</td>";
                                                            echo "<th>Candidate Number</th>";
                                                            echo "<td>{$qualifications[$i]->candidatenumber}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Examination Body</th>";
                                                            echo "<td>{$qualificationDetails[$i]['examinationbody']}</td>";
                                                            echo "<th>Proficiency</th>";
                                                            echo "<td>{$qualificationDetails[$i]['proficiency']}</td>";
                                                        echo "</tr>";

                                                        echo "<tr>";
                                                            echo "<th>Examination Centre</th>";
                                                            echo "<td>{$qualificationDetails[$i]['centrename']}</td>";
                                                            echo "<th>Grade</th>";
                                                            echo "<td style='height:65px'>{$qualificationDetails[$i]['grade']}</td>";
                                                        echo "</tr>";                                            
                                                    echo "</table>";                     
                                            }
                                        }
                                    }
                                ?>
                                <?php if(Yii::$app->user->can('addQualification')):?>
                                    <a class='btn btn-success glyphicon glyphicon-plus pull-right' href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-qualification', 'personid' => $applicant->personid]);?> role='button' style='margin-top:30px;'> Add Qualification</a>
                                <?php endif;?>
                            </div>
                            
                            <?php if(Yii::$app->user->can('viewAcademicQualificationsData')):?> 
                                <br/><br/><br/><br/>
                                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Technical/Vocational Qualifications
                                        <?php if(Yii::$app->user->can('addQualification')):?>        
                                            <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/edit-technical-qualifications', 'personid' => $applicant->personid]);?> role="button"> Edit</a>                                    
                                        <?php endif;?>
                                    </div>
                                    
                                    <?php if ($applicant->otheracademics == NULL || $applicant->otheracademics == " "):?>
                                        </br><p><strong>Applicant has not indicated that they have any additional academic certifications.</strong></p></br>
                                    <?php else:?>
                                        <table class='table table-hover' style='margin: 0 auto;'>
                                            <tr>
                                                <th style="width:35%">Certifications</th>
                                                <td style="width:65%"><?= $applicant->otheracademics?></td>
                                            </tr>
                                        </table>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                                
                                
                            <?php if(Yii::$app->user->can('viewAcademicQualificationsData')):?> 
                                <br/><br/><br/><br/>
                                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Post Secondary Degree
                                        <?php if(Yii::$app->user->can('addQualification')):?>
                                            <?php if(PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true) :?>
                                                <a style="margin-left:10px;" class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/post-secondary-qualification', 'personid' => $applicant->personid, 'action' => 'edit']);?> role="button"> Edit</a>                           
                                                <?php
                                                    if(Yii::$app->user->can('deleteQualification'))
                                                            {
                                                                echo Html::a(' Delete', 
                                                                                ['post-secondary-qualification', 'personid' => $applicant->personid, 'action' => 'delete'], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                 'style' => 'margin-left:10px',
                                                                                ]);
                                                            }
                                                ?>
                                            <?php else:?>
                                                <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/post-secondary-qualification', 'personid' => $applicant->personid, 'action' => 'add']);?> role="button"> Add</a> 
                                             <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                    
                                    <?php if(PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == false) :?>
                                        </br><p><strong>Applicant has not indicated that they have a post secondary degree.</strong></p></br>
                                    <?php else:?>
                                        <table class='table table-hover' style='margin: 0 auto;'>
                                            <tr>
                                                <th style="width:35%">Name of Degree</th>
                                                <td style="width:65%"><?=$post_qualification->name?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%">Awarding Institution</th>
                                                <td style="width:65%"><?=$post_qualification->awardinginstitution?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%">Year Awarded</th>
                                                <td style="width:65%"><?=$post_qualification->yearawarded?></td>
                                            </tr>
                                        </table>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                                
                                
                            <?php if(Yii::$app->user->can('viewAcademicQualificationsData')):?> 
                                <br/><br/><br/><br/>
                                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">External Qualifications
                                        <?php if(Yii::$app->user->can('addQualification')):?>
                                            <?php if(ExternalQualification::getExternalQualifications($applicant->personid) == true) :?>
                                                <a style="margin-left:10px;" class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/external-qualification', 'personid' => $applicant->personid, 'action' => 'edit']);?> role="button"> Edit</a>                           
                                                <?php
                                                    if(Yii::$app->user->can('deleteQualification'))
                                                            {
                                                                echo Html::a(' Delete', 
                                                                                ['external-qualification', 'personid' => $applicant->personid, 'action' => 'delete'], 
                                                                                ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                                                                    'data' => [
                                                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                                                        'method' => 'post',
                                                                                    ],
                                                                                 'style' => 'margin-left:10px',
                                                                                ]);
                                                            }
                                                ?>
                                            <?php else:?>
                                                <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/external-qualification', 'personid' => $applicant->personid, 'action' => 'add']);?> role="button"> Add</a> 
                                             <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                    
                                    <?php if(ExternalQualification::getExternalQualifications($applicant->personid) == false) :?>
                                        </br><p><strong>Applicant has not indicated that they have any external qualifications.</strong></p></br>
                                    <?php else:?>
                                        <table class='table table-hover' style='margin: 0 auto;'>
                                            <tr>
                                                <th style="width:35%">Awarding Institution</th>
                                                <td style="width:65%"><?=$external_qualification->awardinginstitution?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%">Name of Degree</th>
                                                <td style="width:65%"><?=$external_qualification->name?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:35%">Year Awarded</th>
                                                <td style="width:65%"><?=$external_qualification->yearawarded?></td>
                                            </tr>
                                        </table>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="applications">
                            </br>                              
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewApplicationsOffersData') || Yii::$app->user->can('viewApplications')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Applications</div>
                                    <?php
                                        echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                            if (count($first) > 0)
                                            {
                                                echo "<tr>";
                                                    echo "<th rowspan='2' style='vertical-align:top; text-align:left; font-size:1.2em;'>Programme of First Choice</th>";
                                                    echo "<th>Division</th>";
                                                    echo "<td>$firstDetails[0]</td>";
                                                    echo "<th>Programe</th>";
                                                    echo "<td>$firstDetails[1]</td>";                                               
                                                echo "</tr>";
                                                if (count($first) == 1)
                                                {
                                                    echo "<tr>";
                                                         echo "<th>Academic Year</th>";
                                                         echo "<td>$firstDetails[2]</td>";  
                                                    echo "</tr>";
                                                }
                                                if (count($first) == 2)
                                                {
                                                    echo "<tr>";
                                                        echo "<th>CAPE Subjects</th>";
                                                        echo "<td>";
                                                            for ($j = 0; $j < count($first[1]) - 1; $j++) {
                                                                $temp = CapeSubject::find()
                                                                        ->where(['capesubjectid' => $first[1][$j]->capesubjectid])
                                                                        ->one();
                                                                echo $temp->subjectname . "<br/>";
                                                            }
                                                            $temp = CapeSubject::find()
                                                                    ->where(['capesubjectid' => $first[1][$j]->capesubjectid])
                                                                    ->one();
                                                            echo $temp->subjectname;
                                                        echo "</td>";
                                                        echo "<th>Academic Year</th>";
                                                        echo "<td>$firstDetails[2]</td>";  
                                                    echo "</tr>";
                                                }
                                            }

                                            if (count($second) > 0)
                                            {
                                                echo "<tr>";
                                                    echo "<th rowspan='2' style='vertical-align:top; text-align:left; font-size:1.2em;'>Programme of Second Choice</th>";
                                                    echo "<th>Division</th>";
                                                    echo "<td>$secondDetails[0]</td>";
                                                    echo "<th>Programe</th>";
                                                    echo "<td>$secondDetails[1]</td>";                                               
                                                echo "</tr>";
                                                if (count($second) == 1)
                                                {
                                                    echo "<tr>";
                                                         echo "<th>Academic Year</th>";
                                                         echo "<td>$secondDetails[2]</td>";  
                                                    echo "</tr>";
                                                }
                                                if (count($second) == 2)
                                                {
                                                    echo "<tr>";
                                                        echo "<th>CAPE Subjects</th>";
                                                        echo "<td>";
                                                            for ($j = 0; $j < count($second[1]) - 1; $j++) {
                                                                $temp = CapeSubject::find()
                                                                        ->where(['capesubjectid' => $second[1][$j]->capesubjectid])
                                                                        ->one();
                                                                echo $temp->subjectname . "<br/>";
                                                            }
                                                            $temp = CapeSubject::find()
                                                                    ->where(['capesubjectid' => $second[1][$j]->capesubjectid])
                                                                    ->one();
                                                            echo $temp->subjectname;
                                                        echo "</td>";
                                                        echo "<th>Academic Year</th>";
                                                         echo "<td>$secondDetails[2]</td>";  
                                                    echo "</tr>";
                                                }
                                            }

                                            if (count($third) > 0)
                                            {
                                                echo "<tr>";
                                                    echo "<th rowspan='2' style='vertical-align:top; text-align:left; font-size:1.2em;'>Programme of Third Choice</th>";
                                                    echo "<th>Division</th>";
                                                    echo "<td>$thirdDetails[0]</td>";
                                                    echo "<th>Programe</th>";
                                                    echo "<td>$thirdDetails[1]</td>";                                               
                                                echo "</tr>";
                                                if (count($third) == 1)
                                                {
                                                    echo "<tr>";
                                                         echo "<th>Academic Year</th>";
                                                         echo "<td>$thirdDetails[2]</td>";  
                                                    echo "</tr>";
                                                }
                                                if (count($third) == 2)
                                                {
                                                    echo "<tr>";
                                                        echo "<th>CAPE Subjects</th>";
                                                        echo "<td>";
                                                            for ($j = 0; $j < count($third[1]) - 1; $j++) {
                                                                $temp = CapeSubject::find()
                                                                        ->where(['capesubjectid' => $third[1][$j]->capesubjectid])
                                                                        ->one();
                                                                echo $temp->subjectname . "<br/>";
                                                            }
                                                            $temp = CapeSubject::find()
                                                                    ->where(['capesubjectid' => $third[1][$j]->capesubjectid])
                                                                    ->one();
                                                            echo $temp->subjectname;
                                                        echo "</td>";
                                                        echo "<th>Academic Year</th>";
                                                        echo "<td>$thirdDetails[2]</td>"; 
                                                    echo "</tr>";
                                                }
                                            }
                                        echo "</table>"; 
                                    ?>  
                                <?php endif;?>
                            </div>

                            </br>
                            <!--Displays offers with the assumption that only the most recent application associated with a 'suggested' offer has 'isactive' => 1-->
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewApplicationsOffersData') || Yii::$app->user->can('viewOffers')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Offers</div>
                                    <?php
                                    echo "<table class='table table-hover' style='margin: 0 auto;'>";
                                        if ($offers == false)
                                        {
                                            echo "<h4>No offers records found.</h4>";
                                        }
                                        else
                                        {
                                            echo "<tr>";
                                                echo "<th>Related Application</th>";
                                                echo "<th>Offer Type</th>";
                                                echo "<th>Issuing Officer By</th>";
                                                echo "<th>Issue Date</th>";
                                                echo "<th>Revoking Officer</th>";
                                                echo "<th>Revoke Date</th>";
                                            echo "</tr>";
                                            for($i = 0 ; $i<count($offers) ; $i++)
                                            {
                                                echo "<tr>";
                                                    $order = $offers[$i]["ordering"];
                                                    if( $order > 3)
                                                    {
                                                        echo "<td>";
                                                            echo "<span>Modified Offer Details:</span><br/>";
                                                                $isCape = Application::isCapeApplication($offers[$i]["academicofferingid"]);
                                                                if ($isCape == false)
                                                                {
                                                                    $programme_name = Application::getApplicationDetails($offers[$i]["academicofferingid"]);
                                                                    echo "<span>Programme - $programme_name</span>";
                                                                }
                                                                else
                                                                {
                                                                    $capeSubjects = ApplicationCapesubject::getRecords($offers[$i]["applicationid"]);
                                                                    echo "<span>Programme - CAPE</span><br/>";
                                                                    for ($j = 0 ; $j < count($capeSubjects) ; $j++) 
                                                                    {
                                                                        $temp = CapeSubject::find()
                                                                                ->where(['capesubjectid' => $capeSubjects[$j]->capesubjectid])
                                                                                ->one();
                                                                        if ($j==count($capeSubjects)-1)
                                                                            echo $temp->subjectname;
                                                                        else 
                                                                            echo $temp->subjectname . "<br/>";
                                                                    }
                                                                }
                                                        echo "</td>";
                                                    }
                                                    elseif ($order==1)
                                                    {
                                                        echo "<td>Programme Choice 1</td>";
                                                    }
                                                    elseif ($order==2)
                                                    {
                                                        echo "<td>Programme Choice 2</td>";
                                                    }
                                                    elseif ($order==3)
                                                    {
                                                        echo "<td>Programme Choice 3</td>";
                                                    }

                                                    echo "<td>{$offers[$i]["offertype"]}</td>";
                                                    $issuer = Employee::getEmployeeName($offers[$i]["issuedby"]);
                                                    echo "<td>$issuer</td>";
                                                    echo "<td>{$offers[$i]["issuedate"]}</td>";
                                                    if ($offers[$i]["revokedby"] == NULL)
                                                    {
                                                        echo "<td style='text-align:center'>--</td>";   //revokedby
                                                        echo "<td style='text-align:center'>--</td>";   //revokeddate
                                                    }
                                                    else 
                                                    {
                                                        $revoker = Employee::getEmployeeName($offers[$i]["revokedby"]);
                                                        echo "<td>$revoker</td>";
                                                        echo "<td>{$offers[$i]["revokedate"]}</td>";
                                                    }
                                                echo "</tr>";
                                            }
                                        }
                                    echo "</table>"; 
                                    ?>
                                <?php endif;?>
                            </div>
                            
                            
                            <?php if(Yii::$app->user->can('viewDocuments')):?> 
                                <br/>
                                <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Application/Registration Documents
                                        <?php if(Yii::$app->user->can('updateDocuments')):?>
                                            <?php if(empty($document_details)):?>
                                                <a style="margin-left:10px;" class="btn btn-info glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/update-documents', 'personid' => $applicant->personid]);?> role="button"> Add</a>
                                            <?php else:?>
                                                <a style="margin-left:10px;" class="btn btn-success glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/update-documents', 'personid' => $applicant->personid]);?> role="button"> Update</a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                    <?php if(empty($document_details)):?>
                                        <h3>No documents have been submitted for this applicant.</h3>
                                    <?php else:?>
                                        <table class='table table-hover' style='margin: 0 auto;'>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Name</th>
                                                <th>Verifying Officer</th>
                                            </tr>
                                            <?php for($i = 0 ; $i<count($document_details) ; $i++): ?>
                                                <tr>
                                                    <td><?=$document_details[$i]['intent'];?></td>
                                                    <td><?=$document_details[$i]['name'];?></td>
                                                    <td><?=$document_details[$i]['verifier'];?></td>
                                                </tr>
                                            <?php endfor;?>
                                        </table>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




