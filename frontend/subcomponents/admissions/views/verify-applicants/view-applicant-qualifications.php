<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\bootstrap\Modal;

    use frontend\models\ExaminationBody;
    use frontend\models\Subject;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\CsecCentre;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\CsecQualification;
    use frontend\models\ExternalQualification;
    use frontend\models\Application;

    $applicant_name = 'Undefined';
    if ($applicant)
    {
        $applicant_name = $applicant->firstname . ' ' . $applicant->middlename . ' ' . $applicant->lastname;
    }

    $this->title = ' Applicant: ' . $applicant_name;
    $this->params['breadcrumbs'][] = ['label' => $centrename, 
        'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<?= Yii::$app->session->getFlash('error'); ?>
<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>


<a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => 'pending-unlimited', 'applicantusername' => $username]);?> role="button">  View Applicant Profile</a>

<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body">
        <?php
            Modal::begin([
                    'header' => '<h2>Notification</h2>',
                    'id' => 'modal-no-more-qualifications',
                    'size' => 'modal-md',
                ]);
                echo "<p><strong>No records are present to be removed. If you would have previously deleted records, click "
                . "save to finalize the operation</strong>.</p>";
            Modal::end();
        ?>

        <?php
            Modal::begin([
                    'header' => '<h2>Notification</h2>',
                    'id' => 'modal-too-many-qualifications',
                    'size' => 'modal-md',
                ]);
                echo "<p><strong>You have reached your record limit. No more records can be entered.</strong>.</p>";
            Modal::end();
        ?>

        <?php if ($applicant->applicantintentid == 4  || $applicant->applicantintentid == 6 ):?>
        <br/><fieldset style='margin-left:2.5%'>
                <legend><strong>Supporting Documentation Verification</strong></legend>
                <p>
                    Would you like to verify the submission of the applicant's supporting documents at this time?
                     <?= Html::radioList('verify-documents-choice', 'No', ['Yes' => 'Yes' , 'No' => 'No'], ['class'=> 'form_field', 'onclick'=> 'checkVerifyDocuments();']);?>
                    <?=Html::a(' Verify Documents', 
                            ['verify-applicants/view-documents', 'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type, 'personid' => $applicant->personid], 
                            ['class' => 'btn btn-info pull-left',
                                'style' => 'display: none;',
                                'id' => 'go-to-verify-documents'
                            ]);?> 
                </p>
            </fieldset><br/>
        <?php endif;?>    

        <?php if(Application::getAbandonedApplicantApplications($applicant->personid) == true):?>
            <div id="set_application_as_active">
                <?=Html::a(' Reactivate Application', 
                            ['verify-applicants/reactivate-application', 'personid' => $applicant->personid, 'centrename' => $centrename, 'centreid' => $centreid], 
                            ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                'style' => 'margin-right:2.5%',
                                'data' => [
                                    'confirm' => 'Are you sure you want to set this application as active?',
                                    'method' => 'post',
                                ],
                            ]);?>
            </div>
        <?php elseif(Application::getAbandonedApplicantApplications($applicant->personid) == false && Application::getAbandonmentEligibility($applicant->personid)==true):?>
            <div id="set_application_as_abandoned">
                <?=Html::a(' Set As Abandoned', 
                            ['verify-applicants/abandon-application', 'personid' => $applicant->personid, 'centrename' => $centrename, 'centreid' => $centreid], 
                            ['class' => 'btn btn-danger glyphicon glyphicon-remove pull-right',
                                'style' => 'margin-right:2.5%',
                                'data' => [
                                    'confirm' => 'Are you sure you want to set this application as abandoned?',
                                    'method' => 'post',
                                ],
                            ]);?>
            </div>
        <?php endif;?>


        <div id="saved-records">
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'saved-records-form'
                ]); 
            ?>
                <?= Html::hiddenInput('viewApplicantQualifications_baseUrl', Url::home(true)); ?>

                <br/><fieldset style="width:100%">
                    <legend><strong>Certificate Results</strong></legend>
                    <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                        <thead>
                          <tr>
                            <th>Centre Name</th>
                            <th>Examining Body</th>
                            <th>Candidate #</th>
                            <th>Subject</th>
                            <th>Proficiency</th>
                            <th>Grade</th>
                            <th>Year</th>
                            <th>Verified</th>
                            <th>Queried</th>
                            <th>Delete</th>
                          </tr>
                        </thead>

                        <tbody>
                            <?php
                                $id = $applicant->personid;
//                                    $csecqualifications = $dataProvider->getModels();
                                $qual_limit = count($csecqualifications);
                            ?>

                            <?= Html::hiddenInput('record_count', $record_count); ?>
                            <?= Html::hiddenInput('qual_limit', $qual_limit); ?>

                            <?php for ($j=0 ; $j<$record_count ; $j++): ?>
                                <?php if($csecqualifications[$j]->cseccentreid != $centreid):?>
                                <tr style="opacity:0.5">
                                <?php else:?>
                                <tr>
                                <?php endif;?>
                                    <?= Html::activeHiddenInput($csecqualifications[$j], "[$j]csecqualificationid"); ?>
                                    <?= Html::activeHiddenInput($csecqualifications[$j], "[$j]personid"); ?>

                                    <td width = 22.5%>
                                        <?=  $form->field($csecqualifications[$j], "[$j]cseccentreid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                    ArrayHelper::map(CsecCentre::find()->all(), 'cseccentreid', 'name'))?>
                                    </td>


                                    <td width = 10%>
                                        <?=  $form->field($csecqualifications[$j], "[$j]examinationbodyid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationBody::find()->all(), 'examinationbodyid', 'abbreviation'))?>
                                    </td>

                                    <td width = 10%>
                                        <?= $form->field($csecqualifications[$j], "[$j]candidatenumber", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->textInput(); ?>
                                    </td>

                                    <td width = 20%> 
                                        <?= $form->field($csecqualifications[$j], "[$j]subjectid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(Subject::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'subjectid', 'name')) ?>
                                    </td>

                                    <td width = 10%>
                                        <?= $form->field($csecqualifications[$j], "[$j]examinationproficiencytypeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationProficiencyType::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'examinationproficiencytypeid', 'name')) ?>
                                    </td>

                                    <td width = 10%> 
                                        <?= $form->field($csecqualifications[$j], "[$j]examinationgradeid", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->dropDownList(
                                                   ArrayHelper::map(ExaminationGrade::find()->where(['examinationbodyid' => $csecqualifications[$j]->examinationbodyid])
                                                           ->all(), 'examinationgradeid', 'name')); ?>
                                    </td>

                                    <td width = 7.5%>
                                        <?= $form->field($csecqualifications[$j], "[$j]year", ['options' => [
                                                'tag'=>'div',
                                                ],
                                                'template' => '{input}{error}'
                                            ])->textInput(); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($csecqualifications[$j], "[$j]isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width= 5% style="text-align:center">
                                        <?= $form->field($csecqualifications[$j], "[$j]isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td>
                                        <?= Html::a(' ', 
                                                    ['delete-certificate', 'certificate_id' => $csecqualifications[$j]->csecqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                        'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table><br/>
                </fieldset> 

                <div class="form-group pull-right">
                    <!--<a class='btn btn-success glyphicon glyphicon-plus' href=<?=Url::toRoute(['/subcomponents/admissions/view-applicant/add-qualification-from-verify', 'applicantusername' => $username, 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' =>$type ]);?> role='button'> Add Certificate</a>-->

                    <?php if (Yii::$app->user->can('verifyApplicants') && count($csecqualifications)>0): ?>
                        <?= Html::submitButton('Update Certificates', ['class' => 'btn btn-primary', 'onclick'=>'generateQualificationBlanks();', 'style' => 'margin-right: 20px']) ?>
                    <?php endif; ?>

                    <?php if (PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == false):?>
                        <?= Html::submitButton('Save All As Verified', ['class' => 'btn btn-primary', 'name'=>'verified', 'onclick'=>'generateQualificationBlanks();']) ?>
                    <?php endif; ?>
                </div> 


                <?php if($isexternal == 1 && $external_qualification == true) :?>
                    <br/><fieldset>
                        <legend><strong>External Qualifications</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                            <thead>
                                <tr>
                                    <th>Awarding Institution</th>
                                    <th>Name of Degree</th>
                                    <th>Year Awarded</th>
                                    <th>Verified</th>
                                    <th>Queried</th>
                                    <th>Delete</th>
                                </tr>
                            <thead>

                            <tbody>    
                                <tr>
                                    <td width=30% style="vertical-align:middle">
                                        <?= $form->field($external_qualification, 'name')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($external_qualification, 'awardinginstitution')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($external_qualification, 'yearawarded')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($external_qualification, "isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($external_qualification, "isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td style="vertical-align:middle">
                                        <?= Html::a(' ', 
                                                    ['external-qualifications', 'personid' => $applicant->personid, 'action' => 'delete', 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' => $type], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                     'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br/>

                       <?= Html::submitButton('Update External Qualification', ['class' => 'btn btn-primary', 'onclick'=>'generateQualificationBlanks();']) ?>
                    </fieldset>

                <?php elseif($isexternal == 1 && $external_qualification == false) :?>
                    <br/><fieldset style="margin-left:2.5%; width:95%">
                        <legend><strong>External Qualifications</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-hover table-striped" style="width:100%; margin: 0 auto;">
                            <tr>
                                <td>Applicant has not indicated the type of external qualification they possess.</td>
                            </tr>

                            <tr>
                                <?php
                                    $add_role = Url::toRoute(['/subcomponents/admissions/verify-applicants/external-qualifications', 'personid' => $applicant->personid, 'action' => 'add', 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' => $type]);
                                ?>
                                <td><a class="btn btn-success pull-right" href=<?=$add_role?> role="button"> Add External Qualifications</a></td>
                             </tr>
                        </table> 
                    </fieldset>
                <?php endif;?>



                <?php if(PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true) :?>
                    <br/><fieldset>
                        <legend><strong>Post Secondary Degree</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name of Degree</th>
                                    <th>Awarding Institution</th>
                                    <th>Year Awarded</th>
                                    <th>Verified</th>
                                    <th>Queried</th>
                                    <th>Delete</th>
                                </tr>
                            <thead>

                            <tbody>    
                                <tr>
                                    <td width=30% style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'name')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'awardinginstitution')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=30%  style="vertical-align:middle">
                                        <?= $form->field($post_qualification, 'yearawarded')->label("", ['class'=> 'form-label'])->textInput(['maxlength' => true]) ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($post_qualification, "isverified")->checkbox(['label' => NULL, 'value' => 1]); ?>
                                    </td>

                                    <td width=5% style="vertical-align:middle; text-align:center">
                                        <?= $form->field($post_qualification, "isqueried")->checkbox(['label' => NULL]); ?>
                                    </td>

                                    <td style="vertical-align:middle">
                                        <?= Html::a(' ', 
                                                    ['delete-post-secondary-qualification', 'recordid' => $post_qualification->postsecondaryqualificationid], 
                                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                     'style' => 'margin-right:20px',
                                                    ]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br/>
                    </fieldset>
                <?php else:?>
                    <br/><fieldset style="width:100%">
                        <legend><strong>Post Secondary Degree</strong></legend>
                        <table id="post_secondary_qualification_table" class="table table-hover table-striped" style="width:100%; margin: 0 auto;">
                            <tr>
                                <td>Applicant has not indicated that they have a post secondary degree</td>
                            </tr>

                            <tr>
                                <?php
                                    $add_role = Url::toRoute(['/subcomponents/admissions/verify-applicants/add-post-secondary-qualification', 'personid' => $applicant->personid, 'cseccentreid' => $centreid, 'centrename' => $centrename, 'type' => $type]);
                                ?>
                                <td><a class="btn btn-success pull-right" href=<?=$add_role?> role="button"> Add Post Secondary Qualification</a></td>
                             </tr>
                        </table> 
                    </fieldset>
                <?php endif;?>


                <div style="margin-left:2.5%;" class="form-group">
                    <?php if (Yii::$app->user->can('verifyApplicants') &&    count($csecqualifications)>0 && PostSecondaryQualification::getPostSecondaryQualifications($applicant->personid) == true): ?>
                        <br/><?= Html::submitButton('Update Degree', ['class' => 'btn btn-primary', 'onclick'=>'generateQualificationBlanks();']) ?>
                        <?= Html::submitButton('Save All As Verified', ['class' => 'btn btn-primary', 'name'=>'verified', 'onclick'=>'generateQualificationBlanks();']) ?>
                    <?php endif; ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>


        <br/><br/>
        <fieldset style="width:100%">
            <legend><strong>Certification Additions</strong></legend>
            <p style="font-size:18px;"><strong>If you wish to add additional certificates; use the dynamic form found below.</strong></p>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'new-certifcates-form',
                    'action' => Url::to(['verify-applicants/save-new-qualifications',
                                            'personid' => $applicant->personid, 
                                            'centrename' => $centrename, 
                                            'centreid' => $centreid, 
                                            'type' => $type,
                                            'record_count' => $record_count,
                                            'qual_limit' => $qual_limit,
                                        ]),
                ])
            ?>
                <div id="add-certiifcates" class="panel panel-default" style="width:100%; margin: 0 auto;">
                    <div class="panel-heading">
                        <h4>
                            <i class="glyphicon glyphicon-education"></i> New Certificates
                            <?= Html::button(' ', ['style'=>'margin-left:10px', 'class' => 'btn btn-danger btn-sm pull-right glyphicon glyphicon-minus', 'onclick'=>'removeNewCertificate();']);?>
                            <?= Html::button('Add', ['class' => 'btn btn-success btn-sm pull-right glyphicon glyphicon-plus', 'onclick'=>'addNewCertificate();']);?>                         
                        </h4>
                    </div>

                    <div class="panel-body">
                        <div class="container-items">
                            <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto;">
                                <?php for ($i = $record_count ; $i <$qual_limit  ; $i++):?>
                                    <?php $count = ($i-$record_count)+1;?>
                                    <tr id="<?= "qualification[" . $i . "]" ;?>" style="display:none">
                                        <td  width='15%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]cseccentreid")->label("")->dropDownList(CsecCentre::processCentres(), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='12%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationbodyid")->label("")->dropDownList(ExaminationBody::processExaminationBodies(), ['onchange' => 'ProcessExaminationBody(event);', 'style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='15%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]candidatenumber")->label("")->textInput(['maxlength' => true, 'style'=> 'font-size:14px;']) ?>
                                        </td>
                                        <td width='17%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]subjectid")->label("")->dropDownList(Subject::initializeSubjectDropdown($id, $i), ['style'=> 'font-size:14px;']); ?>
                                        </td>
                                        <td width='16%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationproficiencytypeid")->label("")->dropDownList(ExaminationProficiencyType::initializeProficiencyDropdown($id, $i), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='13%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]examinationgradeid")->label("")->dropDownList(ExaminationGrade::initializeGradesDropdown($id, $i), ['style'=> 'font-size:14px;']);?>
                                        </td>
                                        <td width='12%' >
                                            <?= $form->field($csecqualifications[$i], "[{$i}]year")->label("")->dropDownList(Yii::$app->params['years'], ['style'=> 'font-size:14px;']);?>
                                        </td>
                                    </tr>
                                <?php endfor;?>

                            </table>
                        </div>

                        </br>
                        <div id="save-new-certifcates" class="form-group" style="display:none">
                            <?= Html::submitButton('Save New Certificates', ['class' => 'btn btn-primary pull-right', 'onclick'=>'generateQualificationBlanks();']);?>
                        </div>

                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </fieldset>
    </div>
</div>