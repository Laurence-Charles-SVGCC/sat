<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\helpers\FileHelper;
use yii\base\Model;
use common\models\AcademicCareer;
use common\models\User;
use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicOffering;
use frontend\models\Offer;
use frontend\models\ApplicationStatus;
use frontend\models\CapeSubjectGroup;
use frontend\models\AcademicYear;
use frontend\models\CapeSubject;
use frontend\models\EmployeeDepartment;
use frontend\models\Employee;
use frontend\models\ApplicantSearchModel;
use frontend\models\Applicant;
use frontend\models\Address;
use frontend\models\Phone;
use frontend\models\Relation;
use frontend\models\CompulsoryRelation;
use frontend\models\MedicalCondition;
use frontend\models\Institution;
use frontend\models\PersonInstitution;
use frontend\models\UnverifiedInstitution;
use frontend\models\CsecQualification;

use frontend\models\Application;
use frontend\models\ApplicationCapesubject;
use frontend\models\CapeGroup;
use frontend\models\CsecCentre;
use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\ExaminationProficiencyType;
use frontend\models\ExaminationGrade;
use frontend\models\Division;
use frontend\models\NursingAdditionalInfo;
use frontend\models\GeneralWorkExperience;
use frontend\models\Reference;
use frontend\models\CriminalRecord;
use frontend\models\NurseWorkExperience;
use frontend\models\TeachingExperience;
use frontend\models\TeachingAdditionalInfo;
use frontend\models\NursePriorCertification;
use frontend\models\PostSecondaryQualification;
use frontend\models\Rejection;
use frontend\models\RejectionApplications;
use frontend\models\ApplicationPeriod;

use frontend\models\AddressModel;
use frontend\models\ApplicantModel;
use frontend\models\ApplicationModel;
use frontend\models\ApplicationStatusModel;
use frontend\models\CompulsoryRelationModel;
use frontend\models\CriminalRecordModel;
use frontend\models\CsecQualificationModel;
use frontend\models\EmailModel;
use frontend\models\ExternalQualificationModel;
use frontend\models\GeneralWorkExperienceModel;
use frontend\models\MedicalConditionModel;
use frontend\models\NursePriorCertificationModel;
use frontend\models\NurseWorkExperienceModel;
use frontend\models\NursingAdditionalInfoModel;
use frontend\models\OfferModel;
use frontend\models\PhoneModel;
use frontend\models\PostSecondaryQualificationModel;
use frontend\models\ReferenceModel;
use frontend\models\RelationModel;
use frontend\models\TeachingAdditionalInfoModel;
use frontend\models\TeachingExperienceModel;
use frontend\models\UserModel;


class ProcessApplicationsController extends \yii\web\Controller
{

    /**
     * Renders the Application Dashboard
     *
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public function actionIndex()
    {
        $division_id = EmployeeDepartment::getUserDivision();

        //            $sorted_applicants = Applicant::getAuhtorizedStatusCollection($division_id);
        //            $authorized_pending_count = count($sorted_applicants["pending"]);
        //            $authorized_shortlist_count = count($sorted_applicants["shortlist"]);
        //            $authorized_borderline_count = count($sorted_applicants["borderline"]);
        //            $authorized_interviewoffer_count = count($sorted_applicants["interviewees"]);
        //            $authorized_offer_count = count($sorted_applicants["offer"]);
        //            $authorized_rejected_count = count($sorted_applicants["pre_interview_rejects"]);
        //            $authorized_conditional_reject_count = count($sorted_applicants["post_interview_rejects"]);
        //            $exceptions = count($sorted_applicants["exceptions"]);

        $application_count_collection = Applicant::getAuhtorizedStatusCollectionCounts($division_id);
        $authorized_pending_count = $application_count_collection["pending"];
        $authorized_shortlist_count = $application_count_collection["shortlist"];
        $authorized_borderline_count = $application_count_collection["borderline"];
        $authorized_interviewoffer_count = $application_count_collection["interviewees"];
        $authorized_offer_count = $application_count_collection["offer"];
        $authorized_rejected_count = $application_count_collection["pre_interview_rejects"];
        $authorized_conditional_reject_count = $application_count_collection["post_interview_rejects"];
        $exceptions = $application_count_collection["exceptions"];

        return $this->render(
            'index',
            [
                'division_id' => $division_id,

                'authorized_pending' => $authorized_pending_count,
                'authorized_shortlist' => $authorized_shortlist_count,
                'authorized_borderline' => $authorized_borderline_count,
                'authorized_interviewoffer' => $authorized_interviewoffer_count,
                'authorized_offer' => $authorized_offer_count,
                'authorized_rejected' => $authorized_rejected_count,
                'authorized_conditionalofferreject' => $authorized_conditional_reject_count,
                'exceptions' => $exceptions

            ]
        );
    }



    /**
     * Reneders the aplicant list
     *
     * @param type $division_id
     * @param type $application_status
     * @param type $programme
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 20/02/2016
     * Date Last Modified: 20/02/2016
     */
    public function actionViewByStatus($division_id, $application_status, $programme = 0)
    {
        //set session variables to facilitate their use in UpdateView functionality
        Yii::$app->session->set('division_id', $division_id);
        Yii::$app->session->set('application_status', $application_status);

        $applicants = Applicant::getByStatus($application_status, $division_id);

        $data = array();
        foreach ($applicants as $applicant) {
            $app_details = array();

            $app_details['username'] = $applicant->getPerson()->one()->username;
            $app_details['firstname'] = $applicant->firstname;
            $app_details['middlename'] = $applicant->middlename;
            $app_details['lastname'] = $applicant->lastname;


            $applications = Application::find()
                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('ordering ASC')
                ->all();
            $count = count($applications);

            $target_application = Application::getTarget($applications, $application_status);
            $programme_record = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $target_application->applicationid])
                ->one();


            /* Used to facilitate filtering of result set by 'application_status AND 'programme'
               * Results are not constrained on inital view load and when a criteria of "None" is selected
               */
            if ($programme != 0) {
                $offering = AcademicOffering::find()
                    ->where(['academicofferingid' => $target_application->academicofferingid])
                    ->one();
                if ($offering->programmecatalogid != $programme) {
                    continue;
                }
            }

            $app_details['personid'] = $applicant->personid;

            $cape_subjects_names = array();
            $cape_subjects = ApplicationCapesubject::find()
                ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                ->where(
                    [
                        'application.applicationid' => $target_application->applicationid,
                        'application.isactive' => 1,
                        'application.isdeleted' => 0
                    ]
                )
                ->all();

            foreach ($cape_subjects as $cs) {
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
            }

            $app_details['programme'] = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);

            $app_details['subjects_no'] = CsecQualification::getSubjectsPassedCount($applicant->personid);
            $app_details['ones_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 1);
            $app_details['twos_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 2);
            $app_details['threes_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 3);

            $edittable = ($division_id == 1 || $target_application->divisionid == $division_id) ?  "Editable" : "View-Only";
            $app_details['can_edit'] = $edittable;

            $data[] = $app_details;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort' => [
                'defaultOrder' => ['can_edit' => SORT_ASC,  'subjects_no' => SORT_DESC, 'ones_no' => SORT_DESC, 'twos_no' => SORT_DESC, 'threes_no' => SORT_DESC],
                'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no', 'programme', 'can_edit'],
            ]
        ]);

        //Retrieve programmes for current application periods
        $programmes = ProgrammeCatalog::getCurrentProgrammes($division_id);

        $progs = array(0 => 'None');
        foreach ($programmes as $prog) {
            $progs[$prog->programmecatalogid] = $prog->getFullName();
        }

        $status = ApplicationStatus::find()->where(['applicationstatusid' => $application_status])->one();
        $status_name = ($status) ? $status->name : "Exceptions";


        //format filename
        $title = "Title: " . $status_name . " Listing   ";
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;

        return $this->render(
            'view_applications_by_status',
            [
                'dataProvider' => $dataProvider,
                'programmes' => $progs,
                'status_name' => $status_name,
                'application_status' => $application_status,
                'division_id' => $division_id,
                'status' => $status_name,
                'filename' => $filename,
                'programme_id' => $programme
            ]
        );
    }


    /*
      * Purpose: Updates view of applications by selected criteria
      * Created: 27/07/2015 by Gamal Crichton
      * Last Modified: 27/07/2015 by Gamal Crichton
      */
    /**
     * Updates view of applications by selected criteria (application_status + programme)
     *
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public function actionUpdateView()
    {
        if (Yii::$app->request->post()) {
            $request = Yii::$app->request;
            //                $application_status = $request->post('application_status');
            //                $division_id = $request->post('division_id');
            $programme = $request->post('programme');

            $division_id = Yii::$app->session->get('division_id');
            $application_status = Yii::$app->session->get('application_status');
        }
        return $this->redirect(['view-by-status', 'division_id' => $division_id, 'application_status' => $application_status, 'programme' => $programme]);
        //            return self::actionViewByStatus($division_id, $application_status, $programme);
    }


    public function actionViewApplicantCertificates(
        $personid,
        $programme,
        $application_status,
        $programme_id = 0
    ) {
        $user = Yii::$app->user->identity;

        $userDivisionId = UserModel::getUserDivision($user);

        $applicant = ApplicantModel::getApplicantById($personid);

        $applicantUsername = $applicant->getPerson()->one()->username;

        $applicantFullName = ApplicantModel::getFullName($applicant);

        $applicationStatus = $application_status;

        $applicationStatusName =
            ApplicationStatusModel::getApplicationStatusNameById(
                $application_status
            );

        $csecQualifications =
            CsecQualificationModel::getAllCsecQualificationsByPersonId(
                $personid
            );

        $verifiedCsecQualificationsDataProvider =
            new ArrayDataProvider([
                'allModels' =>
                CsecQualificationModel::prepareFormattedVerifiedCsecQualificationListing(
                    $csecQualifications
                ),
                'pagination' => ['pageSize' => 20],
                'sort' => [
                    'defaultOrder' => ['subject' => SORT_ASC],
                    'attributes' => ['subject', 'examinationBody']
                ]
            ]);

        $applications =
            ApplicationModel::getApplicationsByPersonId($personid);

        $programmeChoices =
            ApplicationModel::getFormattedProgrammeChoices(
                $applications,
                $applicationStatus
            );

        $csecCentre =
            CsecQualificationModel::getCentreDetails($csecQualifications[0]);

        // if (
        //     CsecQualificationModel::hasVerifiedCsecQualifications(
        //         $csecQualifications
        //     )
        //     == true
        // ) {
        if (
            (\common\models\ApplicantModel::isApplicantExternal($applicant) == true
                && \common\models\ApplicantModel::allApplicationsVerified($applicant) == true)
            ||
            (\common\models\ApplicantModel::isApplicantExternal($applicant) == false
                && CsecQualificationModel::hasVerifiedCsecQualifications(
                    $csecQualifications
                ) == true)
        ) {
            // Academic qualificaitons tab
            $externalQualification =
                ExternalQualificationModel::getExternalQualificationById($personid);

            $postSecondaryQualification =
                PostSecondaryQualificationModel::getPostSecondaryQualificationsById(
                    $personid
                );

            $applicantProgressMessage =
                ApplicantModel::getProcessedApplicantNotification(
                    $applicant->personid,
                    $user
                );

            // Flags
            $applicantHasOffer = OfferModel::hasOffers($personid);

            $offerDescription =
                $applicantHasOffer == true
                ?
                OfferModel::getPriorityOffer($applicant->personid)
                :
                null;

            $applicantHasCsecEnglish =
                CsecQualificationModel::hasCsecEnglish($personid);

            $applicantHasCsecMathematics =
                CsecQualificationModel::hasCsecMathematics($personid);

            $applicantHasFiveCsecPasses =
                CsecQualificationModel::hasFiveCsecPasses($personid);

            $isDteApplicantWithoutRelevantSciences =
                ($applicant->applicantintentid == 4
                    && CsecQualificationModel::hasDteRelevantSciences($personid) == false);

            $isDneApplicantWithoutRelevantSciences =
                ($applicant->applicantintentid == 6
                    && CsecQualificationModel::hasDneRelevantSciences($personid) == false);

            // Personal Information Tab
            $phone = PhoneModel::getPhoneById($personid);

            $email = EmailModel::getEmailById($personid);

            $permanentaddress =
                AddressModel::getApplicantPermanentAddress($personid);

            $residentaladdress =
                AddressModel::getApplicantResidentialAddress($personid);

            $postaladdress =
                AddressModel::getApplicantPostalAddress($personid);

            $old_beneficiary = false;       //old apply implementation
            $new_beneficiary = false;       //new apply implementation
            $spouse = false;
            $mother = false;
            $father = false;
            $nextofkin = false;
            $old_emergencycontact = false;  //old apply implementation
            $new_emergencycontact = false;  //new apply implementation
            $guardian = false;

            $old_beneficiary =
                RelationModel::getApplicantRelationByType($personid, 6);

            $new_beneficiary =
                CompulsoryRelationModel::getApplicantRelationByType($personid, 6);

            $old_emergencycontact =
                RelationModel::getApplicantRelationByType($personid, 4);

            $new_emergencycontact =
                CompulsoryRelationModel::getApplicantRelationByType($personid, 4);

            $spouse = RelationModel::getApplicantRelationByType($personid, 7);
            $mother = RelationModel::getApplicantRelationByType($personid, 1);
            $father = RelationModel::getApplicantRelationByType($personid, 2);
            $nextofkin = RelationModel::getApplicantRelationByType($personid, 3);
            $guardian = RelationModel::getApplicantRelationByType($personid, 5);

            // Additional Information Tab

            $general_work_experience =
                GeneralWorkExperienceModel::getGeneralWorkExperiencesByPersonId(
                    $personid
                );

            $references = ReferenceModel::getReferencesByPersonId($personid);

            $teaching =
                TeachingExperienceModel::getTeachingExperiencesByPersonId(
                    $personid
                );

            $teachingApplicantHasChildren =
                TeachingAdditionalInfoModel::hasChildren($personid);

            $nursing =
                NurseWorkExperienceModel::getNurseWorkExperienceByPersonId(
                    $personid
                );

            $nursing_certification =
                NursePriorCertificationModel::getNursePriorCertificationsByPersonId(
                    $personid
                );

            $nursinginfo =
                NursingAdditionalInfoModel::getNursingInfoByPersonId($personid);

            $teachinginfo =
                TeachingAdditionalInfoModel::getTeachingInfoByPersonId($personid);

            $criminalrecord =
                CriminalRecordModel::getCriminalRecordByPersonId($personid);

            // secondary attendance
            $academicCareer =
                new AcademicCareer($applicant->getPerson()->one());
            $secondaryAttendances = $academicCareer->getSecondaryAttendances();

            // special permissions
            $applicantHasApplicationsForActiveApplicationPeriod =
                ApplicantModel::hasApplicationsForActiveApplicationPeriod($applicant);

            $administratorAssignedApplicationsExistence =
                ApplicationModel::getAdministratorAssignedApplicationsByPersonId(
                    $personid
                ) == true ?
                true : false;

            $userCanAccessActionColumn =
                (Yii::$app->user->can('Registrar') == true
                    || Yii::$app->user->can("Dean") == true
                    || Yii::$app->user->can("Deputy Dean") == true
                    || Yii::$app->user->can("Admission Team Adjuster") == true) ?
                true : false;

            $userHasRegistrarPriviledges = Yii::$app->user->can('Registrar');

            $currentApplication =
                ApplicationModel::getCurrentApplication($personid, $applications);

            $userIsAuthorizedDasgsDtveMember =
                (in_array($userDivisionId, [4, 5]) == true
                    && (Yii::$app->user->can("Dean") == true
                        || Yii::$app->user->can("Deputy Dean") == true
                        || Yii::$app->user->can("Admission Team Adjuster") == true)
                    &&  $userDivisionId == $currentApplication->divisionid) ? true : false;

            $userIsAuthorizedDteDneMember =
                (in_array($userDivisionId, [6, 7]) == true
                    && (Yii::$app->user->can("Dean") == true
                        || Yii::$app->user->can("Deputy Dean") == true
                        || Yii::$app->user->can("Admission Team Adjuster") == true)) ? true : false;

            $userApplicationResponsePublished =
                ApplicationModel::hasPublishedOffer($applications)
                || ApplicationModel::hasPublishedRejection($applications) ? true : false;

            $userCanUpdateApplicationStatus =
                $applicantHasApplicationsForActiveApplicationPeriod == true
                && ($userApplicationResponsePublished == false
                    && $administratorAssignedApplicationsExistence == false
                    && ($userHasRegistrarPriviledges == true
                        || $userIsAuthorizedDasgsDtveMember == true
                        || $userIsAuthorizedDteDneMember == true)) ? true : false;

            $userCanPerformAdminReset =
                $applicantHasApplicationsForActiveApplicationPeriod == true
                && Yii::$app->user->can('System Administrator') == true ? true : false;

            $userCanIssueUserDefinedOffer =
                $applicantHasApplicationsForActiveApplicationPeriod == true
                && (Yii::$app->user->can('Registrar') == true
                    || (
                        (Yii::$app->user->can('Dean') == true
                            || Yii::$app->user->can('Deputy Dean') == true)
                        && ApplicationModel::hasPublishedRejection($applications) == false)
                    || (Yii::$app->user->can('Admission Team Adjuster') == true
                        && ApplicantModel::isRejected($personid, $applications) == true
                        && ApplicationModel::hasPublishedRejection($applications) == false)) ? true : false;

            $contiguousProgrammeChoicesHaveSameDivision =
                (count($applications) == 2
                    && $currentApplication->ordering == 1
                    && $userDivisionId == $currentApplication->divisionid
                    && $currentApplication->divisionid == $applications[1]->divisionid)
                ||
                (count($applications) == 3
                    && $currentApplication->ordering == 1
                    && $userDivisionId == $currentApplication->divisionid
                    && $currentApplication->divisionid == $applications[1]->divisionid)
                ||
                (count($applications) == 3
                    && $currentApplication->ordering == 2
                    && $userDivisionId == $currentApplication->divisionid
                    && $currentApplication->divisionid == $applications[2]->divisionid);

            $userCanPerformPowerRejection =
                $applicantHasApplicationsForActiveApplicationPeriod == true
                && count($applications) > 1
                && ApplicantModel::isRejected($personid, $applications) == false
                && ApplicationModel::hasPublishedRejection($applications) == false
                && ApplicationModel::hasPublishedOffer($applications) == false
                && (Yii::$app->user->can('Registrar') == true
                    || (
                        (Yii::$app->user->can('Dean') == true
                            || Yii::$app->user->can('Deputy Dean') == true)
                        && $contiguousProgrammeChoicesHaveSameDivision == true))
                ? true : false;

            $aplicantHasMidwiferyApplication =
                ApplicationModel::hasMidwiferyApplication($personid);

            $nursingApplicantHasChildren =
                NursingAdditionalInfoModel::hasChildren($nursinginfo);

            $nursingApplicantIsMember =
                NursingAdditionalInfoModel::isMember($nursinginfo);

            $nursingApplicantHasOtherApplications =
                NursingAdditionalInfoModel::hasOtherApplications($nursinginfo);

            $nursingApplicantHasPreviousApplication =
                NursingAdditionalInfoModel::hasPreviousApplication($nursinginfo);

            $duplicateMessage =
                CsecQualificationModel::generateApplicantHistoryFeedback(
                    $applicant->personid,
                    $csecQualifications[0]
                );

            $fullOffersMade = 0;
            $conditionalOffersMade = 1;
            $programmeExpectedIntake = 0;
            $capeInfo = array();
            $cape = false;

            $academicOffering =
                $currentApplication
                ?
                AcademicOffering::findOne([
                    'academicofferingid' => $currentApplication->academicofferingid
                ])
                :
                null;

            if ($academicOffering == true) {
                $conditionalOffersMade = 3;
                $capeProg = ProgrammeCatalog::findOne(['name' => 'CAPE']);

                $cape =
                    $capeProg
                    ?
                    $academicOffering->programmecatalogid == $capeProg->programmecatalogid
                    :
                    false;

                if ($cape == true) {
                    $capeSubjects =
                        CapeSubject::find()
                        ->innerJoin(
                            'application_capesubject',
                            '`application_capesubject`.`capesubjectid` = `cape_subject`.`capesubjectid`'
                        )
                        ->where([
                            'application_capesubject.applicationid' => $currentApplication->applicationid
                        ])
                        ->all();

                    foreach ($capeSubjects as $cape) {
                        $capeInfo[$cape->subjectname]['offers_made'] =
                            count(Offer::find()
                                ->joinWith('application')
                                ->innerJoin(
                                    '`academic_offering`',
                                    '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
                                )
                                ->innerJoin(
                                    '`application_period`',
                                    '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`'
                                )
                                ->innerJoin(
                                    '`application_capesubject`',
                                    '`application`.`applicationid` = `application_capesubject`.`applicationid`'
                                )
                                ->where([
                                    'application_capesubject.capesubjectid' => $cape->capesubjectid,
                                    'application_period.isactive' => 1,
                                    'application_period.iscomplete' => 0,
                                    'offer.isdeleted' => 0
                                ])
                                ->all());

                        $capeInfo[$cape->subjectname]['capacity'] =
                            $cape->capacity;
                    }
                }

                $fullOffersMade =
                    count(Offer::find()
                        ->innerJoin(
                            'application',
                            '`application`.`applicationid` = `offer`.`applicationid`'
                        )
                        ->innerJoin(
                            'academic_offering',
                            '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
                        )
                        ->where([
                            'offer.isactive' => 1,
                            'offer.isdeleted' => 0,
                            'offer.offertypeid' => 1,
                            'application.isactive' => 1,
                            'application.isdeleted' => 0,
                            'academic_offering.academicofferingid' =>
                            $academicOffering->academicofferingid
                        ])
                        ->all());

                $conditionalOffersMade =
                    count(Offer::find()
                        ->innerJoin(
                            'application',
                            '`application`.`applicationid` = `offer`.`applicationid`'
                        )
                        ->innerJoin(
                            'academic_offering',
                            '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
                        )
                        ->where([
                            'offer.isactive' => 1,
                            'offer.isdeleted' => 0,
                            'offer.offertypeid' => 2,
                            'application.isactive' => 1,
                            'application.isdeleted' => 0,
                            'academic_offering.academicofferingid' =>
                            $academicOffering->academicofferingid
                        ])
                        ->all());

                $programmeExpectedIntake = $academicOffering->spaces;
            }

            return $this->render(
                'view-applicant-verified',
                [
                    'currentApplication' => $currentApplication,
                    'programme' => $programme,
                    'application_status' => $application_status,
                    'programme_id' => $programme_id,

                    'userCanAccessActionColumn' => $userCanAccessActionColumn,
                    'userCanUpdateApplicationStatus' => $userCanUpdateApplicationStatus,
                    'userCanPerformAdminReset' => $userCanPerformAdminReset,
                    'userCanIssueUserDefinedOffer' => $userCanIssueUserDefinedOffer,
                    'userCanPerformPowerRejection' => $userCanPerformPowerRejection,

                    'userDivisionId' => $userDivisionId,
                    'applicant' => $applicant,
                    'applicantUsername' => $applicantUsername,
                    'applicantFullName' => $applicantFullName,
                    'applicationStatus' => $applicationStatus,
                    'applicationStatusName' => $applicationStatusName,
                    'programmeChoices' => $programmeChoices,
                    'centreName' => $csecCentre->name,
                    'cseccentreid' => $csecCentre->cseccentreid,
                    'applicantProgressMessage' => $applicantProgressMessage,

                    // flags
                    'duplicateMessage' => $duplicateMessage,
                    'applicantHasOffer' => $applicantHasOffer,
                    'offerDescription' => $offerDescription,
                    'applicantHasCsecEnglish' => $applicantHasCsecEnglish,
                    'applicantHasCsecMathematics' => $applicantHasCsecMathematics,
                    'applicantHasFiveCsecPasses' => $applicantHasFiveCsecPasses,
                    'isDteApplicantWithoutRelevantSciences' =>
                    $isDteApplicantWithoutRelevantSciences,
                    'isDneApplicantWithoutRelevantSciences' =>
                    $isDneApplicantWithoutRelevantSciences,

                    // offer statistics
                    'fullOffersMade' => $fullOffersMade,
                    'conditionalOffersMade' => $conditionalOffersMade,
                    'programmeExpectedIntake' => $programmeExpectedIntake,
                    'cape' => $cape,
                    'capeInfo' => $capeInfo,

                    // personal-information tab
                    'applicant' => $applicant,
                    'phone' => $phone,
                    'email' => $email,
                    'permanentaddress' => $permanentaddress,
                    'residentaladdress' => $residentaladdress,
                    'postaladdress' => $postaladdress,
                    'old_beneficiary' => $old_beneficiary,
                    'new_beneficiary' => $new_beneficiary,
                    'mother' => $mother,
                    'father' => $father,
                    'nextofkin' => $nextofkin,
                    'old_emergencycontact' => $old_emergencycontact,
                    'new_emergencycontact' => $new_emergencycontact,
                    'guardian' =>  $guardian,
                    'spouse' => $spouse,

                    // additional information tab
                    'general_work_experience' => $general_work_experience,
                    'references' => $references,
                    'teaching' => $teaching,
                    'teachingApplicantHasChildren' =>
                    $teachingApplicantHasChildren,
                    'nursing' => $nursing,
                    'nursing_certification' => $nursing_certification,
                    'nursinginfo' => $nursinginfo,
                    'teachinginfo' => $teachinginfo,
                    'criminalrecord' => $criminalrecord,
                    'aplicantHasMidwiferyApplication' => $aplicantHasMidwiferyApplication,
                    'nursingApplicantHasChildren' => $nursingApplicantHasChildren,
                    'nursingApplicantIsMember' => $nursingApplicantIsMember,
                    'nursingApplicantHasOtherApplications' => $nursingApplicantHasOtherApplications,
                    'nursingApplicantHasPreviousApplication' => $nursingApplicantHasPreviousApplication,

                    // academic qualifications tab
                    'verifiedCsecQualificationsDataProvider' =>
                    $verifiedCsecQualificationsDataProvider,
                    'postSecondaryQualification' => $postSecondaryQualification,
                    'externalQualification' => $externalQualification,

                    //institutions-attended
                    'secondaryAttendances' => $secondaryAttendances
                ]
            );
        } else {
            return $this->render(
                'view-applicant-unverified',
                [
                    'userDivisionId' => $userDivisionId,
                    'applicant' => $applicant,
                    'applicantUsername' => $applicantUsername,
                    'applicantFullName' => $applicantFullName,
                    'applicationStatus' => $applicationStatus,
                    'applicationStatusName' => $applicationStatusName,
                    'programmeChoices' => $programmeChoices,
                    'centreName' => $csecCentre->name,
                    'cseccentreid' => $csecCentre->cseccentreid,
                    'verifiedCsecQualificationsDataProvider' =>
                    $verifiedCsecQualificationsDataProvider,
                ]
            );
        }
    }


    /**
     * Renders the qualification and programme choices of an 'exception' applicant
     *
     * @param type $personid
     * @param type $programme
     * @param type $application_status
     * @param type $programme_id
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 25/08/2016
     * Date Last Modified: 25/08/2016
     */
    public function actionViewExceptionApplicantCertificates($personid)
    {
        $divisionid = (EmployeeDepartment::getUserDivision(Yii::$app->user->identity->personid));

        $duplicate_message = false;

        $applicant = Applicant::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        $username = $applicant->getPerson()->one()->username;

        $applications = Application::find()
            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->where([
                'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
            ])
            ->orderBy('application.ordering ASC')
            ->all();

        $certificates = CsecQualification::getSubjects($personid);

        $application_container = array();

        $target_application = null;

        foreach ($applications as $application) {
            $combined = array();
            $keys = array();
            $values = array();

            array_push($keys, "application");
            array_push($keys, "division");
            array_push($keys, "programme");
            array_push($keys, "status");

            array_push($values, $application);

            $division = Division::find()
                ->where(['divisionid' => $application->divisionid])
                ->one()
                ->abbreviation;
            array_push($values, $division);

            $cape_subjects_names = array();
            $cape_subjects = ApplicationCapesubject::find()
                ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                ->where(
                    [
                        'application.applicationid' => $application->applicationid,
                        'application.isactive' => 1,
                        'application.isdeleted' => 0
                    ]
                )
                ->all();

            $programme_record = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $application->applicationid])
                ->one();

            foreach ($cape_subjects as $cs) {
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
            }

            $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
            array_push($values, $programme_name);

            $status = ApplicationStatus::find()
                ->where(['applicationstatusid' => $application->applicationstatusid])
                ->one()
                ->name;
            array_push($values, $status);

            $combined = array_combine($keys, $values);
            array_push($application_container, $combined);
        }


        /*Get possible duplicates. needs work to deal with multiple years of certificates,
           * but should catch majority
           */
        if ($certificates) {
            $dups = CsecQualification::getPossibleDuplicate($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
            $message = '';
            if ($dups) {
                $dupes = '';
                foreach ($dups as $dup) {
                    $user = User::findOne(['personid' => $dup, 'isdeleted' => 0]);
                    $dupes = $user ? $dupes . ' ' . $user->username : $dupes;
                }
                $message = 'Possible Duplicate of applicant(s) ' . $dupes;
            }
            $reapp = CsecQualification::getPossibleReapplicant($applicant->personid, $certificates[0]->candidatenumber, $certificates[0]->year);
            if ($reapp) {
                $message = $message . ' Applicant applied to College in academic year prior to 2015/2016.';
            }
            if ($dups || $reapp) {
                //Yii::$app->session->setFlash('warning', $message);
                $duplicate_message = $message;
            }
        } else {
            Yii::$app->session->setFlash('error', 'Applicant certificates not yet verified OR Applicant has external Certificates.');
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $certificates,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render(
            'view_exception_applicant_certificates',
            [
                'division_id' => $divisionid,
                'duplicate_message' => $duplicate_message,
                'username' => $username,
                'applicant' => $applicant,
                'applications' => $applications,
                'application_container' => $application_container,
                'dataProvider' => $dataProvider,
            ]
        );
    }


    /**
     * Updates an applicants appropriately
     *
     * @param type $applicationid
     * @param type $new_status
     * @param type $old_status
     * @param type $divisionid
     *
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016 |   2017_08_28
     */
    public function actionUpdateApplicationStatus($applicationid, $new_status, $old_status, $divisionid, $programme, $programme_id)
    {
        $update_candidate = Application::find()
            ->where(['applicationid' => $applicationid])
            ->one();

        $applications = Application::find()
            ->where(['personid' => $update_candidate->personid, 'isactive' => 1, 'isdeleted' => 0])
            ->orderBy('ordering ASC')
            ->all();
        $count = count($applications);

        $position = Application::getPosition($applications, $update_candidate);

        $update_candidate_save_flag = false;
        $applications_save_flag = false;
        $offer_save_flag = false;
        $rejection_save_flag = false;
        $miscellaneous_save_flag = false;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /*
               * If user is a member of "DTE" of "DNE", many condiseration can be negated such as application spanning multiple divsions
               * Also, System Admin is takein into consideration an functionality is dependant on which applicant period is still "Under Review"
               */
            if (
                EmployeeDepartment::getUserDivision() == 6  || EmployeeDepartment::getUserDivision() == 7
                || (EmployeeDepartment::getUserDivision() == 1  && ApplicationPeriod::isDteOrDneApplicationPeriodUnderReview() == true)
            ) {
                // If an application is pending all subsequent applications
                // are set to pending
                if ($new_status == 3) {
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    /*
                      * If previous status was "pre or post interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }


                // If an application is shortlist, borderlined all preceeding applications
                // to reject and subsequent applications are set to pending
                elseif ($new_status == 4  || $new_status == 7) {
                    //updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    /*
                      * If previous status was "pre or post interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }


                /*
                * If an application is interviewoffer;
                * -> all preceeding  applications are set to reject
                * -> all subsequent applications are set to reject
                * -> new conditional offer is created
                */ elseif ($new_status == 8) {
                    //updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 6;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    /**
                     * this should prevent the creation of multiple offers,
                     * which is suspected to occur when internet timeout
                     * during request submission
                     */
                    $existing_current_offer = Offer::find()
                        ->where(['applicationid' => $applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                    if ($existing_current_offer == false) {
                        $offer = new Offer();
                        $offer->applicationid = $applicationid;
                        $offer->offertypeid = 2;
                        $offer->issuedby = Yii::$app->user->getID();
                        $offer->issuedate = date('Y-m-d');
                        $offer_save_flag = $offer->save();
                        if ($offer_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                      * If previous status was "pre-interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescinding rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                }

                /*
                   * If an application is given pre-interview rejection,
                   * -> all preceeding application that are not PostInterviewRejections are rejected
                   * -> all subsequent applications are set to pending
                   */ elseif ($new_status == 6) {
                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    //if  not last application -> updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }
                    /*
                       * If current application being updated is the last application,
                       * then a rejection must be issued
                       */ else {
                        $post_interview_rejections = Rejection::find()
                            ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where([
                                'rejection.rejectiontypeid' => 2, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                'application.isdeleted' => 0, 'application.personid' => $update_candidate->personid,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                            ])
                            ->all();

                        // Pre-Interview Rejection is only created if no Post Interview Rejections exist
                        if ($post_interview_rejections == false) {
                            /**
                             * this should prevent the creation of multiple rejections,
                             * which is suspected to occur when internet timeout
                             * during request submission
                             */
                            $rejection = Rejection::find()
                                ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                                ->where([
                                    'rejection.rejectiontypeid' => 1, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                    'application.isdeleted' => 0, 'application.personid' => $update_candidate->personid,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                    'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                                ])
                                ->one();
                            if ($rejection == false) {
                                //create Rejection record
                                $rejection = new Rejection();
                                $rejection->personid = $update_candidate->personid;
                                $rejection->rejectiontypeid = 1;
                                $rejection->issuedby = Yii::$app->user->getID();
                                $rejection->issuedate = date('Y-m-d');
                                $rejection_save_flag = $rejection->save();
                                if ($rejection_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }

                                //crete associate RejectionApplications records
                                foreach ($applications as $application) {
                                    $temp = new RejectionApplications();
                                    $temp->rejectionid = $rejection->rejectionid;
                                    $temp->applicationid = $application->applicationid;
                                    $miscellaneous_save_flag = $temp->save();
                                    if ($miscellaneous_save_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'Error occured when saving record.');
                                        return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                    }
                                }
                            }
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */
                    if ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }

                /*
                   * If an application is given an 'offer';
                   * -> nothing is done to preceeding applications
                   * -> nothing is done to subsequent applications
                   */ elseif ($new_status == 9  && (Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean'))) {

                    /*** Handle exception case when interviews are skipped  ***/
                    $academicOffering =
                        AcademicOffering::find()
                        ->where(["academicofferingid" => $update_candidate->academicofferingid])
                        ->one();

                    if ($academicOffering->interviewneeded == 0) {
                        if (in_array($old_status, [3, 4, 7]) == true) {
                            // prevent duplicate offers
                            $existing_current_offer =
                                Offer::find()
                                ->where([
                                    'applicationid' => $applicationid,
                                    'offertypeid' => 1,
                                    'isactive' => 1,
                                    'isdeleted' => 0
                                ])
                                ->all();

                            if ($existing_current_offer == false) {
                                //all subsequent applications are rejected
                                if ($count - $position > 1) {
                                    for ($i = $position + 1; $i < $count; $i++) {
                                        $applications[$i]->applicationstatusid = 6;
                                        $applications_save_flag = $applications[$i]->save();
                                        if ($applications_save_flag == false) {
                                            $transaction->rollBack();
                                            Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                        }
                                    }
                                }

                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                                // Generate potentialstudentid
                                else {
                                    $applicant = Applicant::find()
                                        ->where(['personid' => $update_candidate->personid])
                                        ->one();
                                    $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                    $applicant->potentialstudentid = $generated_id;
                                    $applicant->save();
                                }
                            }
                        }

                        /*
                        * If previous status was  "pre-interview-rejection";
                        * -> that rejection is rescinded
                        * -> new offer is created
                        */ elseif ($old_status == 6) {
                            $rejection = Rejection::find()
                                ->where(['personid' => $update_candidate->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();

                            if ($rejection) {
                                $result = Rejection::rescindRejection($update_candidate->personid);
                                if ($result == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }

                            /**
                             * this should prevent the creation of multiple offers,
                             * which is suspected to occur when internet timeout
                             * during request submission
                             */
                            $existing_current_offer = Offer::find()
                                ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();

                            if ($existing_current_offer == false) {
                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                                // Generate potentialstudentid
                                else {
                                    $applicant = Applicant::find()
                                        ->where(['personid' => $update_candidate->personid])
                                        ->one();
                                    $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                    $applicant->potentialstudentid = $generated_id;
                                    $applicant->save();
                                }
                            }
                        }
                    } elseif ($academicOffering->interviewneeded == 1) {
                        /*
                        * If previous status was "InterviewOffer";
                        * ->any subsequent applications are rejected
                        * -> full offer must be created
                        */
                        if ($old_status == 8) {
                            $old_offer = Offer::find()
                                ->where(['applicationid' => $update_candidate->applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                            if ($old_offer == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Applicant corresponding conditional offer was not found');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            } else {
                                /**
                                 * this should prevent the creation of multiple offers,
                                 * which is suspected to occur when internet timeout
                                 * during request submission
                                 */
                                $existing_current_offer = Offer::find()
                                    ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                    ->all();

                                /*
                                * If conditional exists;
                                * it must be published before applicant can be given a full offer
                                */
                                if ($old_offer == true  && $existing_current_offer == false) {
                                    //all subsequent applications are rejected
                                    if ($count - $position > 1) {
                                        for ($i = $position + 1; $i < $count; $i++) {
                                            $applications[$i]->applicationstatusid = 6;
                                            $applications_save_flag = $applications[$i]->save();
                                            if ($applications_save_flag == false) {
                                                $transaction->rollBack();
                                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                            }
                                        }
                                    }

                                    // create offer
                                    $offer = new Offer();
                                    $offer->applicationid = $applicationid;
                                    $offer->offertypeid = 1;
                                    $offer->issuedby = Yii::$app->user->getId();
                                    $offer->issuedate = date("Y-m-d");
                                    $offer_save_flag = $offer->save();
                                    if ($offer_save_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                        return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                    }
                                    // Generate potentialstudentid
                                    else {
                                        $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                        $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                        $applicant->potentialstudentid = $generated_id;
                                        $applicant->save();
                                    }
                                } else {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Applicant conditional offer must be published before full offer can be made');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }

                        /*
                        * If previous status was "post interview rejection";
                        * -> that rejection is rescinded
                        * -> new offer is created
                        */ elseif ($old_status == 10) {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            } else {
                                /**
                                 * this should prevent the creation of multiple offers,
                                 * which is suspected to occur when internet timeout
                                 * during request submission
                                 */
                                $existing_current_offer = Offer::find()
                                    ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                    ->all();

                                if ($existing_current_offer == false) {
                                    // create offer
                                    $offer = new Offer();
                                    $offer->applicationid = $applicationid;
                                    $offer->offertypeid = 1;
                                    $offer->issuedby = Yii::$app->user->getId();
                                    $offer->issuedate = date("Y-m-d");
                                    $offer_save_flag = $offer->save();
                                    if ($offer_save_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                        return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                    }
                                    // Generate potentialstudentid
                                    else {
                                        $applicant = Applicant::find()
                                            ->where(['personid' => $update_candidate->personid])
                                            ->one();
                                        $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                        $applicant->potentialstudentid = $generated_id;
                                        $applicant->save();
                                    }
                                }
                            }
                        }

                        /*
                        * If previous status was  "pre-interview-rejection";
                        * -> that rejection is rescinded
                        * -> new offer is created
                        */ elseif ($old_status == 6) {
                            $rejection = Rejection::find()
                                ->where(['personid' => $update_candidate->personid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();

                            if ($rejection) {
                                $result = Rejection::rescindRejection($update_candidate->personid);
                                if ($result == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }

                            /**
                             * this should prevent the creation of multiple offers,
                             * which is suspected to occur when internet timeout
                             * during request submission
                             */
                            $existing_current_offer = Offer::find()
                                ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();

                            if ($existing_current_offer == false) {
                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                                // Generate potentialstudentid
                                else {
                                    $applicant = Applicant::find()
                                        ->where(['personid' => $update_candidate->personid])
                                        ->one();
                                    $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                    $applicant->potentialstudentid = $generated_id;
                                    $applicant->save();
                                }
                            }
                        }
                    }
                }


                /*
                   * If an application is interview-rejected;
                   * -> all precceding applications are rejected
                   * ->all subsequent applications are set to rejected
                   */ elseif ($new_status == 10  && (Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean'))) {
                    //updates subsequent applications to pending
                    //this is done because admin may want to issue offer to subsequent applicants
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */
                    if ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }

                    /**
                     * this should prevent the creation of multiple rejections,
                     * which is suspected to occur when internet timeout
                     * during request submission
                     */
                    $rejection = Rejection::find()
                        ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('rejection_applications', '`application`.`applicationid` = `rejection_applications`.`applicationid`')     // added by L.Charles (21/06/2017)
                        ->where([
                            'rejection.rejectiontypeid' => 2,  'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                            'application.isdeleted' => 0, 'application.personid' => $update_candidate->personid,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1,
                            'rejection_applications.applicationid' =>  $update_candidate->applicationid, 'rejection_applications.isactive' => 1             // added by L.Charles (21/06/2017)
                        ])
                        ->one();
                    if ($rejection == false) {

                        /***********   Removed by L.Charles as the logice is flawed.  There should be rejection for each post interview rejection decision
                          //Rejection should only be created if this is the last progrmme choice
                          if (Application::istLastChosenApplication($update_candidate) == true)
                          {
                              //create Rejection record
                              $rejection = new Rejection();
                              $rejection->personid = $update_candidate->personid;
                              $rejection->rejectiontypeid = 2;
                              $rejection->issuedby = Yii::$app->user->getID();
                              $rejection->issuedate = date('Y-m-d');
                              $rejection_save_flag = $rejection->save();
                              if ($rejection_save_flag == false)
                              {
                                  $transaction->rollBack();
                                  Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                  return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                              }

                              //create associate RejectionApplications records
                              foreach($applications as $application)
                              {
                                  $temp = new RejectionApplications();
                                  $temp->rejectionid = $rejection->rejectionid;
                                  $temp->applicationid = $application->applicationid;
                                  $miscellaneous_save_flag = $temp->save();
                                  if ($miscellaneous_save_flag == false)
                                  {
                                      $transaction->rollBack();
                                      Yii::$app->session->setFlash('error', 'Error occured when saving record');
                                      return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                  }
                              }
                          }**************************************************************************************************************/

                        // Post-Interview rejection is created for every application that applicant receive rejection after interview for
                        //create Rejection record
                        $rejection = new Rejection();
                        $rejection->personid = $update_candidate->personid;
                        $rejection->rejectiontypeid = 2;
                        $rejection->issuedby = Yii::$app->user->getID();
                        $rejection->issuedate = date('Y-m-d');
                        $rejection_save_flag = $rejection->save();
                        if ($rejection_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }

                        $temp = new RejectionApplications();
                        $temp->rejectionid = $rejection->rejectionid;
                        $temp->applicationid = $update_candidate->applicationid;
                        $miscellaneous_save_flag = $temp->save();
                        if ($miscellaneous_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when saving record');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                }

                $update_candidate->applicationstatusid = $new_status;
                $update_candidate_save_flag = $update_candidate->save();
                if ($update_candidate_save_flag == false) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error occured when saving target application');
                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                } else {
                    $transaction->commit();
                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                }
            }


            /*
               * If user is a member of "DASGS" of "DTVE" many additional considerations have to be  accounted for such as application spanning multiple divisions
               * Also, System Admin is takein into consideration an functionality is dependant on which applicant period is still "Under Review"
               */ elseif (
                EmployeeDepartment::getUserDivision() == 4  || EmployeeDepartment::getUserDivision() == 5
                ||  (EmployeeDepartment::getUserDivision() == 1  && ApplicationPeriod::isDasgsOrDtveApplicationPeriodUnderReview() == true)
            ) {
                /*
                   * If an application is pending all subsequent applications
                   * are set to pending
                   */
                if ($new_status == 3) {
                    /*If new status is 'Pending'
                       * all subsequent applications are set to pending
                       */
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }
                    /*
                      * If previous status was "pre interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }


                /*
                   * If an application is shortlist, borderlined all preceeding applications
                   * to reject and subsequent applications are set to pending
                   */ elseif ($new_status == 4  || $new_status == 7) {
                    //updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    /*
                      * If previous status was "pre interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }


                /*
                   * If an application is interviewoffer;
                   * -> all preceeding  applications are set to reject
                   * -> all subsequent applications are set to reject
                   * -> new conditional offer is created
                   */ elseif ($new_status == 8) {
                    //updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 6;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    /**
                     * this should prevent the creation of multiple offers,
                     * which is suspected to occur when internet timeout
                     * during request submission
                     */
                    $existing_current_offer = Offer::find()
                        ->where(['applicationid' => $applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                    if ($existing_current_offer == false) {
                        $offer = new Offer();
                        $offer->applicationid = $applicationid;
                        $offer->offertypeid = 2;
                        $offer->issuedby = Yii::$app->user->getID();
                        $offer->issuedate = date('Y-m-d');
                        $offer_save_flag = $offer->save();
                        if ($offer_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }

                    /*
                      * If previous status was "pre-interview rejection"
                      * then that rejection is rescinded
                      */
                    if ($old_status == 6) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                }

                /*
                   * If an application is given pre-interview rejection,
                   * -> all precceding applications are rejected
                   * -> all subsequent applications are set to pending
                   */ elseif ($new_status == 6) {
                    //updates preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    //if  not last application -> updates subsequent applications
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }
                    /*
                       * If current application being updated is the last application,
                       * then a rejection must be issued
                       */ else {
                        /**
                         * this should prevent the creation of multiple rejections,
                         * which is suspected to occur when internet timeout
                         * during request submission
                         */
                        $rejection = Rejection::find()
                            ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where([
                                'rejection.rejectiontypeid' => 1, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                'application.isdeleted' => 0, 'application.personid' => $update_candidate->personid,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                            ])
                            ->one();
                        if ($rejection == false) {
                            //create Rejection record
                            $rejection = new Rejection();
                            $rejection->personid = $update_candidate->personid;
                            $rejection->rejectiontypeid = 1;
                            $rejection->issuedby = Yii::$app->user->getID();
                            $rejection->issuedate = date('Y-m-d');
                            $rejection_save_flag = $rejection->save();
                            if ($rejection_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }

                            //crete associate RejectionApplications records
                            foreach ($applications as $application) {
                                $temp = new RejectionApplications();
                                $temp->rejectionid = $rejection->rejectionid;
                                $temp->applicationid = $application->applicationid;
                                $miscellaneous_save_flag = $temp->save();
                                if ($miscellaneous_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving record.');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    /*
                       * If previous status was"conditional offer",
                       * then that offer is revoked
                       */
                    if ($old_status == 8) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 2);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        }
                    }
                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */ elseif ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }
                }

                /*
                   * If an application is given an 'offer';
                   * -> preceeding applications are rejected
                   * -> subsequent applications are rejected
                   */ elseif ($new_status == 9  && (Yii::$app->user->can('Registrar') || Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean'))) {
                    //all subsequent applications are rejected
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 6;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    //rejects all preceeding applications
                    if ($position > 0) {
                        for ($i = $position - 1; $i >= 0; $i--) {
                            if ($applications[$i]->applicationstatusid != 10) {
                                $applications[$i]->applicationstatusid = 6;
                                $applications_save_flag = $applications[$i]->save();
                                if ($applications_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingapplication');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }

                    if ($old_status == 8) {
                        $old_offer = Offer::find()
                            ->where(['applicationid' => $update_candidate->applicationid, 'offertypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                        if ($old_offer == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Applicant corresponding conditional offer was not found');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            /**
                             * this should prevent the creation of multiple offers,
                             * which is suspected to occur when internet timeout
                             * during request submission
                             */
                            $existing_current_offer = Offer::find()
                                ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();

                            /*
                              * If conditional exists;
                              * it must be published before applicant can be given a full offer
                              */
                            if ($old_offer == true  && $existing_current_offer == false) {
                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                                // Generate potentialstudentid
                                else {
                                    $applicant = Applicant::find()
                                        ->where(['personid' => $update_candidate->personid])
                                        ->one();
                                    $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                    $applicant->potentialstudentid = $generated_id;
                                    $applicant->save();
                                }
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Applicant conditional offer must be published before full offer can be made');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    /*
                      * If previous status was "post interview rejection",
                      * -> that rejection is rescinded
                      * -> new offer is created
                      */ elseif ($old_status == 10) {
                        $result = Rejection::rescindRejection($update_candidate->personid);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            /**
                             * this should prevent the creation of multiple offers,
                             * which is suspected to occur when internet timeout
                             * during request submission
                             */
                            $existing_current_offer = Offer::find()
                                ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();

                            if ($existing_current_offer == false) {
                                // create offer
                                $offer = new Offer();
                                $offer->applicationid = $applicationid;
                                $offer->offertypeid = 1;
                                $offer->issuedby = Yii::$app->user->getId();
                                $offer->issuedate = date("Y-m-d");
                                $offer_save_flag = $offer->save();
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                                // Generate potentialstudentid
                                else {
                                    $applicant = Applicant::find()
                                        ->where(['personid' => $update_candidate->personid])
                                        ->one();
                                    $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                    $applicant->potentialstudentid = $generated_id;
                                    $applicant->save();
                                }
                            }
                        }
                    }

                    /*
                       * If previous status was  "pre-interview-rejection";
                       * -> that rejection is rescinded
                       * -> new offer is created
                       */ elseif ($old_status == 6) {
                        $rejection = Rejection::find()
                            ->where(['personid' => $update_candidate->personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();

                        if ($rejection) {
                            $result = Rejection::rescindRejection($update_candidate->personid);
                            if ($result == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when rescind rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }

                        /**
                         * this should prevent the creation of multiple offers,
                         * which is suspected to occur when internet timeout
                         * during request submission
                         */
                        $existing_current_offer = Offer::find()
                            ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                            ->all();

                        if ($existing_current_offer == false) {
                            // create offer
                            $offer = new Offer();
                            $offer->applicationid = $applicationid;
                            $offer->offertypeid = 1;
                            $offer->issuedby = Yii::$app->user->getId();
                            $offer->issuedate = date("Y-m-d");
                            $offer_save_flag = $offer->save();
                            if ($offer_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                            // Generate potentialstudentid
                            else {
                                $applicant = Applicant::find()
                                    ->where(['personid' => $update_candidate->personid])
                                    ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }
                    } else {
                        /**
                         * this should prevent the creation of multiple offers,
                         * which is suspected to occur when internet timeout
                         * during request submission
                         */
                        $existing_current_offer = Offer::find()
                            ->where(['applicationid' => $applicationid, 'offertypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                            ->all();

                        if ($existing_current_offer == false) {
                            // create offer
                            $offer = new Offer();
                            $offer->applicationid = $applicationid;
                            $offer->offertypeid = 1;
                            $offer->issuedby = Yii::$app->user->getId();
                            $offer->issuedate = date("Y-m-d");
                            $offer_save_flag = $offer->save();
                            if ($offer_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating offer');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                            // Generate potentialstudentid
                            else {
                                $applicant = Applicant::find()
                                    ->where(['personid' => $update_candidate->personid])
                                    ->one();
                                $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant->save();
                            }
                        }
                    }
                }


                /*
                   * If an application is interview-rejected;
                   * ->all subsequent applications are set to 'pending'
                   */ elseif ($new_status == 10  && (Yii::$app->user->can('Dean') || Yii::$app->user->can('Deputy Dean'))) {
                    //updates subsequent applications pending
                    if ($count - $position > 1) {
                        for ($i = $position + 1; $i < $count; $i++) {
                            $applications[$i]->applicationstatusid = 3;
                            $applications_save_flag = $applications[$i]->save();
                            if ($applications_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving application');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }
                        }
                    }

                    /*
                       * If previous status was "offer",
                       * then that offer is revoked
                       */
                    if ($old_status == 9) {
                        $result = Offer::rescindOffer($update_candidate->applicationid, 1);
                        if ($result == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when revoke offer');
                            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                        } else {
                            $applicant = Applicant::find()
                                ->where(['personid' => $update_candidate->personid])
                                ->one();
                            $generated_id = Applicant::preparePotentialStudentID($update_candidate->divisionid, $applicant->applicantid, "revoke");
                            $applicant->potentialstudentid = $generated_id;
                            $applicant->save();
                        }
                    }

                    /**
                     * this should prevent the creation of multiple rejections,
                     * which is suspected to occur when internet timeout
                     * during request submission
                     */
                    $rejection = Rejection::find()
                        ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where([
                            'rejection.rejectiontypeid' => 2,  'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                            'application.isdeleted' => 0, 'application.personid' => $update_candidate->personid,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                        ])
                        ->one();
                    if ($rejection == false) {
                        //Rejection should only be created if this is the last progrmme choice
                        if (Application::istLastChosenApplication($update_candidate) == true) {
                            //create Rejection record
                            $rejection = new Rejection();
                            $rejection->personid = $update_candidate->personid;
                            $rejection->rejectiontypeid = 2;
                            $rejection->issuedby = Yii::$app->user->getID();
                            $rejection->issuedate = date('Y-m-d');
                            $rejection_save_flag = $rejection->save();
                            if ($rejection_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                            }

                            //create associate RejectionApplications records
                            foreach ($applications as $application) {
                                $temp = new RejectionApplications();
                                $temp->rejectionid = $rejection->rejectionid;
                                $temp->applicationid = $application->applicationid;
                                $miscellaneous_save_flag = $temp->save();
                                if ($miscellaneous_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving record');
                                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                                }
                            }
                        }
                    }
                }

                $update_candidate->applicationstatusid = $new_status;
                $update_candidate_save_flag = $update_candidate->save();
                if ($update_candidate_save_flag == false) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error occured when saving target application');
                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                } else {
                    $transaction->commit();
                    // cant be redirected to applicant as their following application may not be related to the official's division
                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error occured processing your request');
            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), $old_status, $programme_id);
        }
    }


    /**
     * Prepares data that is to be displayed on the "View Applicant Details" view
     *
     * @return type
     *
     * Author: Laurence Charles
     * Date created: 23/02/2016
     * Date Last Modified: 23/02/2016
     */
    public function actionViewApplicantDetails($personid, $programme, $application_status)
    {
        $id = $personid;
        $applicant = Applicant::findByPersonID($id);

        $permanentaddress = Address::getAddress($id, 1);
        $residentaladdress = Address::getAddress($id, 2);
        $postaladdress = Address::getAddress($id, 3);
        $addresses = [$permanentaddress, $residentaladdress, $postaladdress];

        $phone = Phone::findPhone($id);

        //Relations
        $beneficiary = false;
        $spouse = false;
        $mother = false;
        $father = false;
        $nextofkin = false;
        $emergencycontact = false;
        $guardian = false;

        $beneficiary = CompulsoryRelation::getRelationRecord($id, 6);
        $emergencycontact = CompulsoryRelation::getRelationRecord($id, 4);

        $spouse = Relation::getRelationRecord($id, 7);
        $mother = Relation::getRelationRecord($id, 1);
        $father = Relation::getRelationRecord($id, 2);
        $nextofkin = Relation::getRelationRecord($id, 3);
        $guardian = Relation::getRelationRecord($id, 5);

        $medicalConditions = MedicalCondition::getMedicalConditions($id);

        $applicantDetails = $applicant->variableDetails();

        $applications = Application::getApplications($id);
        $first = array();
        $firstDetails = array();
        $second = array();
        $secondDetails = array();
        $third = array();
        $thirdDetails = array();

        $db = Yii::$app->db;
        foreach ($applications as $application) {
            $capeSubjects = null;
            $isCape = null;
            $division = null;
            $programme = null;
            $d = null;
            $p = null;
            if ($application->ordering == 1) {
                array_push($first, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true) {
                    $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                    array_push($first, $capeSubjects);
                }
                $d = Division::find()
                    ->where(['divisionid' => $application->divisionid])
                    ->one();
                $division = $d->name;
                array_push($firstDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($firstDetails, $fullname);
            } elseif ($application->ordering == 2) {
                array_push($second, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true) {
                    $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                    array_push($second, $capeSubjects);
                }
                $d = Division::find()
                    ->where(['divisionid' => $application->divisionid])
                    ->one();
                $division = $d->name;
                array_push($secondDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($secondDetails, $fullname);
            } elseif ($application->ordering == 3) {
                array_push($third, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true) {
                    $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                    array_push($third, $capeSubjects);
                }
                $d = Division::find()
                    ->where(['divisionid' => $application->divisionid])
                    ->one();
                $division = $d->name;
                array_push($thirdDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                        . " FROM  academic_offering "
                        . " JOIN programme_catalog"
                        . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                        . " JOIN qualification_type"
                        . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($thirdDetails, $fullname);
            }
        }

        $preschools = PersonInstitution::getPersonInsitutionRecords($id, 1);
        $preschoolNames = array();
        if ($preschools != false) {
            foreach ($preschools as $preschool) {
                $name = null;
                $record = null;
                $record = Institution::find()
                    ->where(['institutionid' => $preschool->institutionid])
                    ->one();
                $name = $record->name;
                array_push($preschoolNames, $name);
            }
        }

        $primaryschools = PersonInstitution::getPersonInsitutionRecords($id, 2);
        $primaryschoolNames = array();
        if ($primaryschools != false) {
            foreach ($primaryschools as $primaryschool) {
                $name = null;
                $record = null;
                $record = Institution::find()
                    ->where(['institutionid' => $primaryschool->institutionid])
                    ->one();
                $name = $record->name;
                array_push($primaryschoolNames, $name);
            }
        }

        $secondaryschools = PersonInstitution::getPersonInsitutionRecords($id, 3);
        $secondaryschoolNames = array();
        if ($secondaryschools != false) {
            foreach ($secondaryschools as $secondaryschool) {
                $name = null;
                $record = null;
                $record = Institution::find()
                    ->where(['institutionid' => $secondaryschool->institutionid])
                    ->one();
                $name = $record->name;
                array_push($secondaryschoolNames, $name);
            }
        }

        $tertieryschools = PersonInstitution::getPersonInsitutionRecords($id, 4);
        $tertieryschoolNames = array();
        if ($tertieryschools != false) {
            foreach ($tertieryschools as $tertieryschool) {
                $name = null;
                $record = null;
                $record = Institution::find()
                    ->where(['institutionid' => $tertieryschool->institutionid])
                    ->one();
                $name = $record->name;
                array_push($tertieryschoolNames, $name);
            }
        }

        $qualifications = CsecQualification::getQualifications($id);
        $qualificationDetails = array();

        if ($qualifications != false) {
            $keys = ['centrename', 'examinationbody', 'subject', 'proficiency', 'grade'];
            foreach ($qualifications as $qualification) {
                $values = array();
                $combined = array();
                $centre = CsecCentre::find()
                    ->where(['cseccentreid' => $qualification->cseccentreid])
                    ->one();
                array_push($values, $centre->name);
                $examinationbody = ExaminationBody::find()
                    ->where(['examinationbodyid' => $qualification->examinationbodyid])
                    ->one();
                array_push($values, $examinationbody->abbreviation);
                $subject = Subject::find()
                    ->where(['subjectid' => $qualification->subjectid])
                    ->one();
                array_push($values, $subject->name);
                $proficiency = ExaminationProficiencyType::find()
                    ->where(['examinationproficiencytypeid' => $qualification->examinationproficiencytypeid])
                    ->one();
                array_push($values, $proficiency->name);
                $grade = ExaminationGrade::find()
                    ->where(['examinationgradeid' => $qualification->examinationgradeid])
                    ->one();
                array_push($values, $grade->name);
                $combined = array_combine($keys, $values);
                array_push($qualificationDetails, $combined);
                $values = null;
                $combined = null;
            }
        }

        $certificates = NursePriorCertification::getCertifications($id);
        $nursinginfo = NursingAdditionalInfo::getNursingInfo($id);
        $teaching_info = TeachingAdditionalInfo::getTeachingInfo($id);
        $generalExperiences = GeneralWorkExperience::getGeneralWorkExperiences($id);
        $references = Reference::getReferences($id);
        $criminalrecord = CriminalRecord::getCriminalRecord($id);
        $nurseExperience = NurseWorkExperience::getNurseWorkExperience($id);
        $teachingExperiences = TeachingExperience::getTeachingExperiences($id);

        $qualification = PostSecondaryQualification::find()
            ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        return $this->render('view_applicant_details', [
            'applicant' => $applicant,
            'addresses' => $addresses,
            'phone' => $phone,
            'beneficiary' => $beneficiary,
            'mother' => $mother,
            'father' => $father,
            'nextofkin' => $nextofkin,
            'emergencycontact' => $emergencycontact,
            'guardian' =>  $guardian,
            'spouse' => $spouse,
            'medicalConditions' => $medicalConditions,
            'applicantDetails' => $applicantDetails,
            'qualifications' => $qualifications,
            'qualificationDetails' => $qualificationDetails,
            'first' => $first,
            'firstDetails' => $firstDetails,
            'second' => $second,
            'secondDetails' => $secondDetails,
            'third' => $third,
            'thirdDetails' => $thirdDetails,
            'preschools' => $preschools,
            'preschoolNames' => $preschoolNames,
            'primaryschools' => $primaryschools,
            'primaryschoolNames' => $primaryschoolNames,
            'secondaryschools' => $secondaryschools,
            'secondaryschoolNames' => $secondaryschoolNames,
            'tertieryschools' => $tertieryschools,
            'tertieryschoolNames' => $tertieryschoolNames,
            'teaching_info' => $teaching_info,
            'nursinginfo' => $nursinginfo,
            'generalExperiences' => $generalExperiences,
            'references' => $references,
            'criminalrecord' => $criminalrecord,
            'nurseExperience' => $nurseExperience,
            'teachingExperiences' => $teachingExperiences,
            'certificates' => $certificates,
            'qualification' => $qualification,

            'programme' => $programme,
            'application_status' => $application_status,
        ]);
    }



    /*
       * Encodes the academic offergins; essential for the dependant dropdown widget
       *
       *
       * @param type $personid
       * @return type
       *
       * Author: Laurence Charles
       * Date Created: 06/11/2015
       * Date Last Modified:06/11/2015 | 06/05/2016
       */
    public function actionAcademicOffering($personid)
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $division_id = $parents[0];
                $out = self::getAcademicOfferingList($division_id, $personid);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        //            echo Json::encode(['output'=>'', 'selected'=>'']);
    }



    /**
     * Retrieves the academic offerins; essential for the dependant dropdown widget
     *
     * @param type $division_id
     * @return array
     *
     * Author: Laurence Charles
     * Date Created: 06/11/2015
     * Date Last Modified:06/11/2015 | 06/05/2016 | 31/05/2016
     */
    public static function getAcademicOfferingList($division_id, $personid)
    {
        $intent = Applicant::getApplicantIntent($personid);
        $db = Yii::$app->db;

        if ($intent == null) {
            $records = $db->createCommand(
                "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation, intent_type.name AS 'programmetype'"
                    . " FROM programme_catalog"
                    . " JOIN academic_offering"
                    . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " JOIN application_period"
                    . " ON academic_offering.applicationperiodid = application_period.applicationperiodid"
                    . " JOIN intent_type"
                    . " ON programme_catalog.programmetypeid = intent_type.intenttypeid"
                    . " WHERE academic_offering.isactive=1"
                    . " AND academic_offering.isdeleted=0"
                    . " AND application_period.iscomplete = 0"
                    . " AND application_period.isactive = 1"
                    . " AND programme_catalog.departmentid"
                    . " IN ("
                    . " SELECT departmentid"
                    . " FROM department"
                    . " WHERE divisionid = " . $division_id
                    . " );"
            )
                ->queryAll();
        } else {
            if ($intent == 1  || $intent == 4 || $intent == 6) {       //if user is applying for full time programme
                $programmetypeid = 1;   //used to identify full time programmes
            } elseif ($intent == 2 || $intent == 3  || $intent == 5  || $intent == 7) {      //if user is applying for part time
                $programmetypeid = 2;  //will be used to identify part time programmes
            }

            $records = $db->createCommand(
                "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation, intent_type.name AS 'programmetype'"
                    . " FROM programme_catalog"
                    . " JOIN academic_offering"
                    . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " JOIN application_period"
                    . " ON academic_offering.applicationperiodid = application_period.applicationperiodid"
                    . " JOIN intent_type"
                    . " ON programme_catalog.programmetypeid = intent_type.intenttypeid"
                    . " WHERE academic_offering.isactive=1"
                    . " AND academic_offering.isdeleted=0"
                    . " AND application_period.iscomplete = 0"
                    . " AND application_period.isactive = 1"
                    . " AND programme_catalog.programmetypeid= " . $programmetypeid
                    . " AND programme_catalog.departmentid"
                    . " IN ("
                    . " SELECT departmentid"
                    . " FROM department"
                    . " WHERE divisionid = " . $division_id
                    . " );"
            )
                ->queryAll();
        }


        $arr = array();
        foreach ($records as $record) {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "id");
            array_push($keys, "name");
            $k1 = strval($record["academicofferingid"]);

            if ($record["programmetype"] == "part") {
                $k2 = strval($record["abbreviation"] . " " . $record["name"] . " " . $record["specialisation"] . "(Part-Time)");
            } else {
                $k2 = strval($record["abbreviation"] . " " . $record["name"] . " " . $record["specialisation"]);
            }

            array_push($values, $k1);
            array_push($values, $k2);
            $combined = array_combine($keys, $values);
            array_push($arr, $combined);
            $combined = null;
            $keys = null;
            $values = null;
        }
        return $arr;
    }


    /**
     * Generates an alternative application and offer for an applicant
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 07/05/2016
     * Date Last Modified: 07/05/2016
     */
    public function actionCustomOffer($personid, $programme, $application_status)
    {
        date_default_timezone_set('America/St_Vincent');

        $application_save_flag = false;
        $applicationcapesubject_save_flag = false;
        $rejectionapplications_save_flag = false;
        $rejection_save_flag = false;

        $id = $personid;
        $capegroups = CapeGroup::getGroups();
        $applicationcapesubject = array();
        $groups = CapeGroup::getGroups();
        $groupCount = count($groups);
        $application = new Application();

        //Create blank records to accommodate capesubject-application associations
        for ($i = 0; $i < $groupCount; $i++) {
            $temp = new ApplicationCapesubject();
            //Values giving default value so as to facilitate validation (selective saving will be implemented)
            $temp->capesubjectid = 0;
            $temp->applicationid = 0;
            array_push($applicationcapesubject, $temp);
        }

        //Flags
        $application_load_flag = false;
        $application_save_flag = false;
        $capesubject_load_flag = false;
        $capesubject_validation_flag = false;
        $capesubject_save_flag = false;

        if ($post_data = Yii::$app->request->post()) {              //if post request made
            $application_load_flag = $application->load($post_data);

            if ($application_load_flag == true) {       //if application load operation is successful
                $application->personid = $id;
                $application->applicationtimestamp = date('Y-m-d H:i:s');
                $application->submissiontimestamp = date('Y-m-d H:i:s');

                $current_applications = Application::getVerifiedApplications($personid);

                /* if applicant has less than three applications;
                   * -> the first alternative offer has an ordering of 4
                   * else
                   * -> it have an ordering 1 higher than the last active application
                   */
                if (count($current_applications) <= 3) {
                    $application->ordering = 4;
                } else {
                    $last_priority = end($current_applications)->ordering;
                    $application->ordering = $last_priority + 1;
                }

                $application->ipaddress = Yii::$app->request->getUserIP();
                $application->browseragent = Yii::$app->request->getUserAgent();
                $application->applicationstatusid = 9;

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $application_save_flag = $application->save();
                    if ($application_save_flag == false) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occurred when saving application.');
                    } else {
                        /*
                           * all current applications must be rejected
                           */
                        $temp_save_flag = true;
                        $save_flag = true;
                        foreach ($current_applications as $app) {
                            if ($app->applicationstatusid != 10) {
                                $app->applicationstatusid = 6;
                                $temp_save_flag = $app->save();
                                if ($temp_save_flag == false) {
                                    $save_flag = false;
                                    break;
                                }
                            }
                        }

                        if ($save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred when rejecting  previous application.');
                        } else {
                            $application_ids = array();
                            foreach ($current_applications as $record) {
                                $application_ids[] = $record->applicationid;
                            }

                            /* If offer has been issued it must be rescinded */
                            $offers = Offer::find()
                                ->where(['applicationid' => $application_ids, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                            if ($offers) {
                                $offer_flag = true;
                                $offer_save_flag = true;
                                foreach ($offers as $offer) {
                                    $offer_flag = Offer::rescindOffer($offer->applicationid, $offer->offertypeid);
                                    if ($offer_flag == false) {
                                        $offer_save_flag = false;
                                        break;
                                    }
                                }
                                if ($offer_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occurred when rescinding offer.');
                                    return self::actionCustomOffer($personid, $programme, $application_status);
                                }
                            }

                            /*If rejections exist they must also be rescinded*/
                            $rejection = Rejection::find()
                                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                            $rescind_rejection_flag = false;
                            if ($rejection) {
                                $rescind_rejection_flag = Rejection::rescindRejection($id);
                                if ($rescind_rejection_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occurred when rescinding rejection(s).');
                                    return self::actionCustomOffer($personid, $programme, $application_status);
                                }
                            }


                            $isCape = Application::isCAPEApplication($application->academicofferingid);
                            if ($isCape == true) {       //if application is for CAPE programme
                                $capesubject_load_flag = Model::loadMultiple($applicationcapesubject, $post_data);
                                if ($capesubject_load_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occurred when loading capesubjects.');
                                } else {
                                    $capesubject_validation_flag = Model::validateMultiple($applicationcapesubject);
                                    if ($capesubject_validation_flag == false) {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error occurred when validating capesubjects.');
                                    } else {
                                        //CAPE subject selection is only updated if 3-4 subjects have been selected
                                        $selected = 0;
                                        foreach ($applicationcapesubject as $subject) {
                                            if ($subject->capesubjectid != 0) {           //if valid subject is selected
                                                $selected++;
                                            }
                                        }

                                        if ($selected >= 2 && $selected <= 4) {            //if valid number of CAPE subjects have been selected
                                            $temp_status = true;
                                            foreach ($applicationcapesubject as $subject) {
                                                $subject->applicationid = $application->applicationid;      //updates applicationid

                                                if ($subject->capesubjectid != 0 && $subject->applicationid != 0) {       //if none is selected then reocrd should not be saved
                                                    $capesubject_save_flag = $subject->save();
                                                    if ($capesubject_save_flag == false) {          //CapeApplicationSubject save operation fails
                                                        $temp_status = false;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($temp_status == false) {
                                                $transaction->rollBack();
                                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving capesubject associations.');
                                            }
                                        } else {         //if incorrect number of CAPE subjects selected.
                                            $transaction->rollBack();
                                            Yii::$app->getSession()->setFlash('error', 'CAPE subject selection has not been saved. You must select 2(min) to 4(max) CAPE subjects.');
                                            return self::actionViewApplicantCertificates($personid, $programme, 9);
                                        }
                                    }
                                }
                            } //endif isCape

                            // create offer
                            $offer = new Offer();
                            $offer->applicationid = $application->applicationid;
                            $offer->offertypeid = 1;
                            $offer->issuedby = Yii::$app->user->getId();
                            $offer->issuedate = date("Y-m-d");
                            $new_offer_save_flag = $offer->save();
                            if ($new_offer_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving new offer.');
                            } else {
                                //generate potenialstudentid
                                $applicant_save_flag = false;
                                $applicant = Applicant::find()
                                    ->where(['personid' => $personid])
                                    ->one();
                                $generated_id = Applicant::preparePotentialStudentID($application->divisionid, $applicant->applicantid, "generate");
                                $applicant->potentialstudentid = $generated_id;
                                $applicant_save_flag = $applicant->save();

                                if ($applicant_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving applicant record.');
                                } else {
                                    $transaction->commit();
                                    return self::actionViewApplicantCertificates($personid, $programme, $application_status);
                                }
                            }
                        } //if rejections successful
                    } //endif application_save_flag == true
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occurred when processing request.');
                }
            }   //end-if application load
            else {
                Yii::$app->getSession()->setFlash('error', 'Error occurred loading application record.');
            }
        }   //end-if POST operation

        return $this->render('custom_offer', [
            'application' => $application,
            'applicationcapesubject' =>  $applicationcapesubject,
            'capegroups' => $capegroups,
            'personid' => $personid,
        ]);
    }



    /**
     * Resets all programme choices to Pending
     *
     * @param type $personid
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 31/08/2016
     * Date Last Modified: 31/08/2016
     */
    public function actionResetApplications($personid)
    {
        $applications = Application::getVerifiedApplications($personid);

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($applications as $application) {
                $save_flag = false;
                $application->applicationstatusid = 3;
                $save_flag = $application->save();
                if ($save_flag == false) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occurred resetting application.');
                    return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), 0);
                }
            }

            $transaction->commit();
            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), 0);
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error occured processing your request');
            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), 0);
        }
    }



    /**
     * Rejects target application and other consecutive applications belonging to the same division
     *
     * @param type $target_application
     * @param type $personid
     * @param type $programme
     * @param type $application_status
     * @param type $programme_id
     * @return type
     *
     * Author: Laurence Charles
     * Date Created: 31/08/2016
     * Date Last Modified: 31/08/2016
     */
    public function actionPowerRejection($personid, $programme, $application_status, $programme_id)
    {
        // if (EmployeeDepartment::getUserDivision() == 1)
        // {
        //     Yii::$app->session->setFlash('error', 'Error occured retreiving active applications.');
        //     return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
        // }

        $current_applications = Application::getVerifiedApplications($personid);
        if ($current_applications == false) {
            Yii::$app->session->setFlash('error', 'Error occured retreiving active applications.');
            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
        }


        $target_application = false;
        foreach ($current_applications as $app) {
            $istarget = Application::isTarget($current_applications, $application_status, $app);
            if ($istarget == true) {
                $target_application = $app;
            }
        }
        if ($target_application == false) {
            Yii::$app->session->setFlash('error', 'Error occured retreiving target application.');
            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
        }


        $application_count = count($current_applications);
        $position = Application::getPosition($current_applications, $target_application);

        //if only one programme choice exists or there are no subsequent application, exit funciton
        if ($application_count == 1  || $application_count - $position <= 1) {
            Yii::$app->session->setFlash('error', 'No subsequent application are present for rejection');
            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
        }


        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $application_save_flag = false;

            if ($application_count == 2) {
                $last_application = $current_applications[$position + 1];
                if ($target_application->divisionid == $last_application->divisionid) {
                    $target_application->applicationstatusid = 6;
                    $application_save_flag = $target_application->save();
                    if ($application_save_flag == false) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error occured saving target application');
                        return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                    }

                    $last_application->applicationstatusid = 6;
                    $application_save_flag = $last_application->save();
                    if ($application_save_flag == false) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error occured saving last application');
                        return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                    }

                    /**
                     * this should prevent the creation of multiple rejections,
                     * which is suspected to occur when internet timeout
                     * during request submission
                     */
                    $rejection = Rejection::find()
                        ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where([
                            'rejection.rejectiontypeid' => 1, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                            'application.isdeleted' => 0, 'application.personid' => $personid,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                            'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                        ])
                        ->one();
                    if ($rejection == false) {
                        //create Rejection record
                        $rejection = new Rejection();
                        $rejection->personid = $personid;
                        $rejection->rejectiontypeid = 1;
                        $rejection->issuedby = Yii::$app->user->getID();
                        $rejection->issuedate = date('Y-m-d');
                        $rejection_save_flag = $rejection->save();
                        if ($rejection_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }

                        //crete associate RejectionApplications records
                        foreach ($current_applications as $appl) {
                            $temp = new RejectionApplications();
                            $temp->rejectionid = $rejection->rejectionid;
                            $temp->applicationid = $appl->applicationid;
                            $miscellaneous_save_flag = $temp->save();
                            if ($miscellaneous_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when saving rejection-applications record.');
                                return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                            }
                        }
                    }
                }
            } elseif ($application_count == 3) {
                if ($position == 0) {
                    $second_application =  $current_applications[$position + 1];
                    $last_application =  $current_applications[$position + 2];

                    //if all three application belong to the same division all are rejected
                    if ($target_application->divisionid == $second_application->divisionid) {
                        $target_application->applicationstatusid = 6;
                        $application_save_flag = $target_application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured saving application');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }

                        $second_application->applicationstatusid = 6;
                        $application_save_flag = $second_application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured saving application');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }
                    }

                    if ($second_application->divisionid == $last_application->divisionid) {
                        $last_application->applicationstatusid = 6;
                        $application_save_flag = $last_application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured saving application');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }

                        /**
                         * this should prevent the creation of multiple rejections,
                         * which is suspected to occur when internet timeout
                         * during request submission
                         */
                        $rejection = Rejection::find()
                            ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where([
                                'rejection.rejectiontypeid' => 1, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                'application.isdeleted' => 0, 'application.personid' => $personid,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                            ])
                            ->one();
                        if ($rejection == false) {
                            //create Rejection record
                            $rejection = new Rejection();
                            $rejection->personid = $personid;
                            $rejection->rejectiontypeid = 1;
                            $rejection->issuedby = Yii::$app->user->getID();
                            $rejection->issuedate = date('Y-m-d');
                            $rejection_save_flag = $rejection->save();
                            if ($rejection_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                            }

                            //crete associate RejectionApplications records
                            foreach ($current_applications as $appl) {
                                $temp = new RejectionApplications();
                                $temp->rejectionid = $rejection->rejectionid;
                                $temp->applicationid = $appl->applicationid;
                                $miscellaneous_save_flag = $temp->save();
                                if ($miscellaneous_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when savingrejection-applications record.');
                                    return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                                }
                            }
                        }
                    }
                } elseif ($position == 1) {
                    $last_application = $current_applications[$position + 1];
                    if ($target_application->divisionid == $last_application->divisionid) {
                        $target_application->applicationstatusid = 6;
                        $application_save_flag = $target_application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured saving target application');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }

                        $last_application->applicationstatusid = 6;
                        $application_save_flag = $last_application->save();
                        if ($application_save_flag == false) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Error occured saving last application');
                            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                        }

                        /**
                         * this should prevent the creation of multiple rejections,
                         * which is suspected to occur when internet timeout
                         * during request submission
                         */
                        $rejection = Rejection::find()
                            ->innerJoin('application', '`application`.`personid` = `rejection`.`personid`')
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where([
                                'rejection.rejectiontypeid' => 1, 'rejection.isactive' => 1, 'rejection.isdeleted' => 0,
                                'application.isdeleted' => 0, 'application.personid' => $personid,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0, 'application_period.isactive' => 1
                            ])
                            ->one();
                        if ($rejection == false) {
                            //create Rejection record
                            $rejection = new Rejection();
                            $rejection->personid = $personid;
                            $rejection->rejectiontypeid = 1;
                            $rejection->issuedby = Yii::$app->user->getID();
                            $rejection->issuedate = date('Y-m-d');
                            $rejection_save_flag = $rejection->save();
                            if ($rejection_save_flag == false) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'Error occured when creating rejection');
                                return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                            }

                            //crete associate RejectionApplications records
                            foreach ($current_applications as $appl) {
                                $temp = new RejectionApplications();
                                $temp->rejectionid = $rejection->rejectionid;
                                $temp->applicationid = $appl->applicationid;
                                $miscellaneous_save_flag = $temp->save();
                                if ($miscellaneous_save_flag == false) {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', 'Error occured when saving rejection-applications record.');
                                    return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
                                }
                            }
                        }
                    }
                }
            }

            $transaction->commit();
            //                return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), 0);
            return self::actionViewByStatus(EmployeeDepartment::getUserDivision(), 3);
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error occured processing your request');
            return self::actionViewApplicantCertificates($personid, $programme, $application_status, $programme_id);
        }
    }



    public function actionGenerateEligibleListing($status)
    {
        $dataProvider = false;

        if ($status == "Pending") {
            $application_status = 3;
        } elseif ($status == "Borderline") {
            $application_status = 7;
        } elseif ($status == "Shortlist") {
            $application_status = 4;
        }

        $applicants = Applicant::getByStatus($application_status, 1);

        $data = array();
        foreach ($applicants as $applicant) {
            $app_details = array();

            $minimum_subjects_passed = CsecQualification::hasFiveCsecPasses($applicant->personid);
            $has_english = CsecQualification::hasCsecEnglish($applicant->personid);
            if ($minimum_subjects_passed == false  || $has_english == false) {
                continue;
            }

            $app_details['username'] = $applicant->getPerson()->one()->username;
            $app_details['firstname'] = $applicant->firstname;
            $app_details['middlename'] = $applicant->middlename;
            $app_details['lastname'] = $applicant->lastname;

            $applications = Application::find()
                ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('ordering ASC')
                ->all();
            $count = count($applications);

            $target_application = Application::getTarget($applications, $application_status);
            $programme_record = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $target_application->applicationid])
                ->one();

            $app_details['personid'] = $applicant->personid;

            $cape_subjects_names = array();
            $cape_subjects = ApplicationCapesubject::find()
                ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                ->where(
                    [
                        'application.applicationid' => $target_application->applicationid,
                        'application.isactive' => 1,
                        'application.isdeleted' => 0
                    ]
                )
                ->all();

            foreach ($cape_subjects as $cs) {
                $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
            }

            $app_details['programme'] = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);

            $app_details['subjects_no'] = CsecQualification::getSubjectsPassedCount($applicant->personid);
            $app_details['ones_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 1);
            $app_details['twos_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 2);
            $app_details['threes_no'] = CsecQualification::getSubjectGradesCount($applicant->personid, 3);

            $data[] = $app_details;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
            'sort' => [
                'defaultOrder' => ['subjects_no' => SORT_DESC, 'ones_no' => SORT_DESC, 'twos_no' => SORT_DESC, 'threes_no' => SORT_DESC],
                'attributes' => ['subjects_no', 'ones_no', 'twos_no', 'threes_no', 'programme'],
            ]
        ]);

        $title = "Title: " . $status . " Applicants With 5 CSEC Pases Including English Language";
        $date =  "  Date Generated: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "  Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;

        return $this->renderPartial('minimum_requirements', [
            'dataProvider' => $dataProvider,
            'filename' => $filename,
        ]);
    }


    /**
     * Reset Applicant;
     * Delete all offer
     * Delete all rejections
     * Sets all application choices to Pending
     *
     * Author: charles.laurence1@gmail.com
     * Created: 2018_04_10
     * Modified: 2018_04_10
     */
    public function actionFullApplicantReset($personid, $programme, $application_status, $programme_id)
    {
        $reset_failed = false;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $offers = array();
            $rejections = array();

            $applications = Application::getVerifiedApplications($personid);

            // retreives offers and rejections
            foreach ($applications as $application) {
                $offer = Offer::find()
                    ->where(['applicationid' => $application->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                if ($offer == true) {
                    $offers[] = $offer;
                }

                $rejection = Rejection::find()
                    ->innerJoin('rejection_applications', '`rejection`.`rejectionid` = `rejection_applications`.`rejectionid`')
                    ->where(['rejection.isactive' => 1, 'rejection.isdeleted' => 0, 'rejection_applications.applicationid' => $application->applicationid])
                    ->one();
                if ($rejection == true) {
                    $rejections[] = $rejection;
                }
            }

            if (count($offers) > 0) {
                foreach ($offers as $offer) {
                    if ($offer->isactive == 1  &&  $offer->isdeleted == 0) {
                        $offer->isactive = 0;
                        $offer->isdeleted = 1;
                        if ($offer->save() == false) {
                            $reset_failed = true;
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred deleting offers.');
                        }
                    }
                }
            }

            if (count($rejections) > 0) {
                foreach ($rejections as $rejection) {
                    if ($rejection->isactive == 1  &&  $rejection->isdeleted == 0) {
                        $rejection->isactive = 0;
                        $rejection->isdeleted = 1;
                        if ($rejection->save() == false) {
                            $reset_failed = true;
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occurred deleting rejections.');
                        }
                    }
                }
            }

            foreach ($applications as $application) {
                $application->applicationstatusid = 3;
                if ($application->save() == false) {
                    $reset_failed = true;
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occurred resetting applications to pending.');
                }
            }

            if ($reset_failed == false) {
                $transaction->commit();
                Yii::$app->getSession()->setFlash('success', 'Full applicant reset successful.');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error occured processing your request');
        }

        return $this->redirect([
            'view-applicant-certificates',
            'personid' => $personid,
            'programme' => $programme,
            'application_status' => $application_status
        ]);
    }

    public function actionEditContactDetails(
        $personid,
        $programme,
        $application_status,
        $programme_id
    ) {
        $user = UserModel::getUserById($personid);
        $phone = PhoneModel::getPhoneById($personid);
        $email = EmailModel::getEmailById($personid);


        if ($post_data = Yii::$app->request->post()) {
            if (
                $phone == true && $phone->load($post_data) == true
                && $email == true  && $email->load($post_data) == true
                && $user == true
            ) {
                $user->email = $email->email;

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if (
                        $phone->save() == true
                        && $email->save() == true
                        && $user->save() == true
                    ) {
                        $transaction->commit();
                        // return $this->redirect(\Yii::$app->request->getReferrer());
                        return $this->redirect([
                            "view-applicant-certificates",
                            "personid" => $personid,
                            "programme" => $programme,
                            "application_status" => $application_status,
                            "programme_id" => $programme_id
                        ]);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash(
                            'error',
                            'Error occured when updating contact info.'
                        );
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash(
                        'error',
                        'Operation failed.'
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Error occured loading form.'
                );
            }
        }

        return $this->render(
            'edit-contact-details',
            [
                'applicantname' => UserModel::getUserFullname($user),
                'user' => $user,
                'phone' => $phone,
                'email' => $email,
                'personid' => $user->personid,
                'programme' => $programme,
                'application_status' => $application_status,
                'programme_id' => $programme_id
            ]
        );
    }
}
