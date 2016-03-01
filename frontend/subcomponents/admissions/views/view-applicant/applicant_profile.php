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
    
    /* @var $this yii\web\View */
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
                <h1 class="custom_h1"><?=$applicant->title . ". " . $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></h1>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
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
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="personal_information"> 
                            <br/>
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                            <?php if(Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewGeneral')):?>    
                                <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">General
                                <?php if(Yii::$app->user->can('editGeneral')):?>        
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-general', 'personid' => $applicant->personid]);?> role="button"> Edit</a>                                    
                                <?php endif;?>
                                </div>

                                <!-- Table -->
                                <table class="table table-hover" style="margin: 0 auto;">
                                    <tr>
                                        <td rowspan="3"> 
                                            <?php if($applicant->photopath == NULL || strcmp($applicant->photopath, "") ==0 ): ?>
                                                <?php if (strcasecmp($applicant->gender, "male") == 0): ?>
                                                    <img src="<?=Url::to('../images/avatar_male(150*150).png');?>" alt="avatar_male" class="img-rounded">
                                                <?php elseif (strcasecmp($applicant->gender, "female") == 0): ?>
                                                    <img src="<?=Url::to('../images/avatar_female(150*150).png');?>" alt="avatar_female" class="img-rounded">
                                                <?php endif;?>
                                            <?php else: ?>
                                                    <img src="<?=$applicant->photopath;?>" alt="student_picture" class="img-rounded">
                                            <?php endif;?>
                                        </td>
                                        <th>Student ID</th>
                                        <td><?=$user->username;?></td>
                                        <th>Applicant ID</th>
                                        <td><?=$student->applicantname;?></td>
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
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-contact-details', 'personid' => $applicant->personid]);?> role="button"> Edit</a>
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
                                    <a class="btn btn-info glyphicon glyphicon-pencil pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-addresses', 'personid' => $applicant->personid]);?> role="button"> Edit</a>
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
                                    <a class="btn btn-success glyphicon glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-optional-relative', 'personid' => $applicant->personid]);?> role="button"> Add</a>
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $old_beneficiary->relationid]);?> role="button"> Edit</a>
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
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-compulsory-relative', 'personid' => $applicant->personid, 'recordid' => $new_beneficiary->compulsoryrelationid]);?> role="button"> Edit</a>
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $old_emergencycontact->relationid]);?> role="button"> Edit</a>
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
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-compulsory-relative', 'personid' => $applicant->personid, 'recordid' => $new_emergencycontact->compulsoryrelationid]);?> role="button"> Edit</a>
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $spouse->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>    
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['profile/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $spouse->relationid], 
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
                                                        <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $mother->relationid]);?> role="button"> Edit</a>
                                                    </div>
                                                <?php endif;?>
                                                <?php if(Yii::$app->user->can('deleteRelative')):?> 
                                                    <div style="margin-top:10px">
                                                        <?=Html::a(' Delete', 
                                                                    ['profile/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $mother->relationid], 
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $father->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>  
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>     
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['profile/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $father->relationid], 
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $nextofkin->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?> 
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['profile/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $nextofkin->relationid], 
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
                                                            <a class="btn btn-info glyphicon glyphicon-pencil" href=<?=Url::toRoute(['/subcomponents/students/profile/edit-optional-relative', 'personid' => $applicant->personid, 'recordid' => $guardian->relationid]);?> role="button"> Edit</a>
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if(Yii::$app->user->can('deleteRelative')):?>     
                                                        <div style="margin-top:10px">
                                                            <?=Html::a(' Delete', 
                                                                        ['profile/delete-optional-relative', 'personid' => $applicant->personid, 'recordid' => $guardian->relationid], 
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
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="medical_details">                              
                            </br>                              
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewMedicalDetailsData') || Yii::$app->user->can('viewMedicalCondition')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Medical Conditions
                                        <?php if(Yii::$app->user->can('addMedicalCondition')):?>   
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-medical-condition', 'personid' => $applicant->personid]);?> role="button"> Add</a>
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
                                                    $delete_hyperlink = Url::toRoute(['/subcomponents/students/profile/delete-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid]);
                                                    $edit_hyperlink = Url::toRoute(['/subcomponents/students/profile/edit-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid]);

                                                    echo "<tr>";
                                                        echo "<th rowspan='2' style='vertical-align:top; text-align:center; font-size:1.2em;'>$medicalCondition->medicalcondition";
                                                            echo "<div style='margin-top:20px'>";
                                                                if(Yii::$app->user->can('deleteMedicalCondition'))
                                                                {
                                                                    echo Html::a(' Delete', 
                                                                                        ['profile/delete-medical-condition', 'personid' => $applicant->personid, 'recordid' => $medicalCondition->medicalconditionid], 
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
                            <h2 class="custom_h2">Additional Details</h2>
                            </br>
                            <img style="display: block; margin: auto;" src ="<?=Url::to('../images/under_construction.jpg');?>" alt="Under Construction">
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="academic_history"> 
                            </br>                              
                            <div class="panel panel-default" style="width:95%; margin: 0 auto;">
                                <?php if(Yii::$app->user->can('viewInstitutionsData')):?>
                                    <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Pre-School Attendance
                                        <?php if(Yii::$app->user->can('addSchool')):?>
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-school', 'personid' => $applicant->personid, 'levelid' => 1]);?> role="button"> Add</a>
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
                                                        $pre_delete_link = Url::toRoute(['/subcomponents/students/profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid]);
                                                        $pre_edit_link =  Url::toRoute(['/subcomponents/students/profile/edit-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid, 'levelid' => 1]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $preschools[$i]->personinstitutionid], 
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
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-school', 'personid' => $applicant->personid, 'levelid' => 2]);?> role="button"> Add</a>
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
                                                        $pri_delete_link = Url::toRoute(['/subcomponents/students/profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid]);
                                                        $pri_edit_link =  Url::toRoute(['/subcomponents/students/profile/edit-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid, 'levelid' => 2]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $primaryschools[$i]->personinstitutionid], 
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
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-school', 'personid' => $applicant->personid,  'studentregistrationid' => $studentregistrationid, 'levelid' => 3]);?> role="button"> Add</a>
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
                                                        $sec_delete_link = Url::toRoute(['/subcomponents/students/profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid]);
                                                        $sec_edit_link =  Url::toRoute(['/subcomponents/students/profile/edit-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid, 'levelid' => 3]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $secondaryschools[$i]->personinstitutionid], 
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
                                            <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/students/profile/add-school', 'personid' => $applicant->personid,  'studentregistrationid' => $studentregistrationid, 'levelid' => 4]);?> role="button"> Add</a>
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
                                                        $ter_delete_link = Url::toRoute(['/subcomponents/students/profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid]);
                                                        $ter_edit_link =  Url::toRoute(['/subcomponents/students/profile/edit-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid, 'levelid' => 4]);

                                                        if(Yii::$app->user->can('deleteSchool'))
                                                        {
                                                            echo "<td style='vertical-align:middle; text-align:center; height:75px'>";
                                                                echo Html::a(' Delete', 
                                                                                ['profile/delete-school', 'personid' => $applicant->personid, 'recordid' => $tertiaryschools[$i]->personinstitutionid], 
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
                                                $editlink = Url::toRoute(['/subcomponents/students/profile/edit-qualification', 'personid' => $applicant->personid, 'recordid' => $qualificationid]);
                                                $deletelink = Url::toRoute(['/subcomponents/students/profile/delete-qualification', 'personid' => $applicant->personid, 'recordid' => $qualificationid]);

                                                echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>#$val ";
                                                    if(Yii::$app->user->can('deleteQualification'))
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
                                                    if(Yii::$app->user->can('editQualification'))
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
                                    <a class='btn btn-success glyphicon glyphicon-plus pull-right' href=<?=Url::toRoute(['/subcomponents/students/profile/add-qualification', 'personid' => $applicant->personid]);?> role='button' style='margin-top:30px;'> Add Qualification</a>
                                <?php endif;?>
                            </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




