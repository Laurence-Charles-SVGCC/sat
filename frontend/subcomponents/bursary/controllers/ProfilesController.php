<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\ApplicantModel;
use common\models\ApplicationModel;
use common\models\ApplicationAmendmentPaymentForm;
use common\models\ApplicationSubmissionPaymentForm;
use common\models\BillingChargeModel;
use common\models\BillingModel;
use common\models\BursaryAccountSearchForm;
use common\models\EmailModel;
use common\models\PaymentMethodModel;
use common\models\PhoneModel;
use common\models\ReceiptModel;
use common\models\RelationModel;
use common\models\StudentHoldModel;
use common\models\StudentModel;
use common\models\UserModel;


class ProfilesController extends \yii\web\Controller
{
    public function actionSearch()
    {
        $user = Yii::$app->user->identity;
        $model = new BursaryAccountSearchForm();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->validate() == true) {
                $customer = UserModel::findUserByApplicantIDOrStudentID($model->id);
                $customerClassification = UserModel::getUserClassification($customer);
                if ($customerClassification == "Student") {
                    return $this->redirect(
                        ["student-profile", "username" => $customer->username]
                    );
                } elseif ($customerClassification == "Successful Applicant") {
                    return $this->redirect(
                        ["successful-applicant-profile", "username" => $customer->username]
                    );
                } elseif ($customerClassification == "Completed Applicant") {
                    return $this->redirect(
                        ["completed-applicant-profile", "username" => $customer->username]
                    );
                } elseif ($customerClassification == "Abandoned Applicant") {
                    return $this->redirect(
                        ["abandoned-applicant-profile", "username" => $customer->username]
                    );
                } elseif ($customerClassification == "Incomplete Applicant") {
                    return $this->redirect(
                        ["incomplete-applicant-profile", "username" => $customer->username]
                    );
                }
            }
        }
        return $this->render("search", ["model" => $model]);
    }


    public function actionStudentProfile($username)
    {
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);
        $displayPicture = ApplicantModel::generateDisplayPicture($applicant);
        $student = StudentModel::getStudentByPersonid($customer->personid);
        $email = EmailModel::getEmailByPersonid($customer->personid);
        $phone = PhoneModel::getPhoneByPersonid($customer->personid);
        $programme = StudentModel::getCurrentProgramme($student);
        $personalEmail = $email->email;
        $institutionalEmail = $customer->email;

        $beneficiary = RelationModel::getBeneficiaryRelation($customer);
        if ($beneficiary == true) {
            $beneficiaryDetails =
                RelationModel::getRelationFullname($beneficiary)
                . "\n"
                . RelationModel::getRelationContactDetails($beneficiary);
        } else {
            $beneficiaryDetails = null;
        }

        $financialHolds =
            StudentHoldModel::getFinancialHoldsByPersonID($customer->personid);

        $formattedStudentFinancialHolds =
            StudentHoldModel::prepareFormattedStudentFinancialHolds(
                $financialHolds
            );

        $financialHoldsDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" => $formattedStudentFinancialHolds,
                    "pagination" => ["pageSize" => 20],
                    "sort" => [
                        "defaultOrder" => ["id" => SORT_ASC],
                        "attributes" => [
                            "id", "holdName", "appliedBy", "holdStatus"
                        ]
                    ]
                ]
            );

        $receipts = ReceiptModel::getReceiptsByCustomerId($customer->personid);

        $receiptsDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    ReceiptModel::prepareSuccessfulApplicantFormattedReceiptListing(
                        $receipts
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["datePaid" => SORT_ASC],
                        "attributes" => ["total", "applicationPeriod", "datePaid"]
                    ]
                ]
            );

        $studentRegistrations =
            StudentModel::getStudentRegistrations($customer->personid);

        return $this->render(
            "student-profile",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "applicant" => $applicant,
                "programme" => $programme,
                "displayPicture" => $displayPicture,
                "student" => $student,
                "phone" => $phone,
                "beneficiaryDetails" => $beneficiaryDetails,
                "personalEmail" => $personalEmail,
                "institutionalEmail" => $institutionalEmail,
                "financialHoldsDataProvider" => $financialHoldsDataProvider,
                "status" => "Enrolled Student",
                "dataProvider" => $receiptsDataProvider,
                "studentRegistrations" => $studentRegistrations,
            ]
        );
    }


    public function actionSuccessfulApplicantProfile($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);
        $displayPicture = ApplicantModel::generateDisplayPicture($applicant);
        $student = StudentModel::getStudentByPersonid($customer->personid);
        $email = EmailModel::getEmailByPersonid($customer->personid);
        $phone = PhoneModel::getPhoneByPersonid($customer->personid);

        $programme =
            ApplicantModel::getUnenrolledSuccessfulApplicantProgramme(
                $applicant
            );

        $personalEmail = $email->email;
        $institutionalEmail = $customer->email;

        $beneficiary = RelationModel::getBeneficiaryRelation($customer);
        if ($beneficiary == true) {
            $beneficiaryDetails =
                RelationModel::getRelationFullname($beneficiary)
                . "\n"
                . RelationModel::getRelationContactDetails($beneficiary);
        } else {
            $beneficiaryDetails = null;
        }

        $receipts = ReceiptModel::getReceiptsByCustomerId($customer->personid);
        $receiptsDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    ReceiptModel::prepareSuccessfulApplicantFormattedReceiptListing(
                        $receipts
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["datePaid" => SORT_ASC],
                        "attributes" => ["total", "applicationPeriod", "datePaid"]
                    ]
                ]
            );

        return $this->render(
            "successful-applicant-profile",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "applicant" => $applicant,
                "programme" => $programme,
                "displayPicture" => $displayPicture,
                "student" => $student,
                "phone" => $phone,
                "beneficiaryDetails" => $beneficiaryDetails,
                "personalEmail" => $personalEmail,
                "institutionalEmail" => $institutionalEmail,
                "status" => "Successful Applicant Awaiting Enrollment",
                "dataProvider" => $receiptsDataProvider
            ]
        );
    }


    public function actionCompletedApplicantProfile($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);
        $displayPicture = ApplicantModel::generateDisplayPicture($applicant);
        $email = EmailModel::getEmailByPersonid($customer->personid);
        $phone = PhoneModel::getPhoneByPersonid($customer->personid);
        $personalEmail = $email->email;
        $institutionalEmail = $user->email;
        $beneficiary = RelationModel::getBeneficiaryRelation($user);
        $beneficiaryDetails = RelationModel::getBeneficiarySummary($beneficiary);

        $showMissingApplicationSubmissionBillingChargeNotification = false;
        $showApplicantSubmissionPaymentForm = false;
        $applicantSubmissionPaymentForm = null;

        $showMissingApplicationAmendmentBillingChargeNotification = false;
        $showApplicantAmendmentPaymentForm = false;
        $applicantAmendmentPaymentForm = null;

        $targetApplicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $activeApplicationSubmissionBillingCharge =
            BillingChargeModel::getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
                $targetApplicationPeriodId
            );
        if ($activeApplicationSubmissionBillingCharge == false) {
            $showMissingApplicationSubmissionBillingChargeNotification = true;
        }

        $activeApplicationAmendmentBillingCharge =
            BillingChargeModel::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                $targetApplicationPeriodId
            );

        if ($activeApplicationAmendmentBillingCharge == false) {
            $showMissingApplicationAmendmentBillingChargeNotification = true;
        }

        $applicantApplicationSubmissionBilling =
            BillingModel::getApplicantApplicationSubmissionBilling($applicant);

        $applicantApplicationAmendmentBilling =
            BillingModel::getApplicantApplicationAmendmentBilling($applicant);

        if (
            $activeApplicationSubmissionBillingCharge == true
            && $applicantApplicationSubmissionBilling == false
        ) { // Generate ApplicationSubmissionPaymentForm
            $showApplicantSubmissionPaymentForm = true;
            $applicantSubmissionPaymentForm = new ApplicationSubmissionPaymentForm();
            $applicantSubmissionPaymentForm->customerId = $customer->personid;
            $applicantSubmissionPaymentForm->applicationPeriodId =
                $targetApplicationPeriodId;
            $applicantSubmissionPaymentForm->billingChargeId =
                $activeApplicationSubmissionBillingCharge->id;
            $applicantSubmissionPaymentForm->username = $username;
            $applicantSubmissionPaymentForm->fullName = $userFullname;
            $applicantSubmissionPaymentForm->autoPublish = 1;
            $applicantSubmissionPaymentForm->amount =
                $activeApplicationSubmissionBillingCharge->cost;
        } else if (
            $activeApplicationSubmissionBillingCharge == true
            && $applicantApplicationSubmissionBilling == true
            && $activeApplicationAmendmentBillingCharge == true
            && $applicantApplicationAmendmentBilling == false
        ) { // Generate ApplicationAmendmentPaymentForm
            $showApplicantAmendmentPaymentForm = true;
            $applicantAmendmentPaymentForm = new ApplicationAmendmentPaymentForm();
            $applicantAmendmentPaymentForm->customerId = $customer->personid;
            $applicantAmendmentPaymentForm->applicationPeriodId =
                $targetApplicationPeriodId;
            $applicantAmendmentPaymentForm->billingChargeId =
                $activeApplicationAmendmentBillingCharge->id;
            $applicantAmendmentPaymentForm->username = $username;
            $applicantAmendmentPaymentForm->fullName = $userFullname;
            $applicantAmendmentPaymentForm->autoPublish = 1;
            $applicantAmendmentPaymentForm->amount =
                $activeApplicationAmendmentBillingCharge->cost;
        } elseif (
            $activeApplicationSubmissionBillingCharge == true
            && $applicantApplicationSubmissionBilling == true
            && $activeApplicationAmendmentBillingCharge == true
            && $applicantApplicationSubmissionBilling == true
        ) {
            $showApplicantSubmissionPaymentForm = false;
            $showApplicantAmendmentPaymentForm = false;
        }

        $paymentMethods =
            PaymentMethodModel::generatePaymentMethodsAssociativeArray(
                PaymentMethodModel::getActivePaymentMethods()
            );

        $receipts = ReceiptModel::getReceiptsByCustomerId($customer->personid);

        $receiptsDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    ReceiptModel::prepareCompletedApplicantFormattedReceiptListing(
                        $receipts
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["datePaid" => SORT_ASC],
                        "attributes" => [
                            "total",
                            "applicationPeriod",
                            "datePaid"
                        ]
                    ]
                ]
            );

        $applicationDetails =
            ApplicationModel::getFormattedProgrammeChoices($applicant->personid);

        return $this->render(
            "completed-applicant-profile",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "applicant" => $applicant,
                "displayPicture" => $displayPicture,
                "phone" => $phone,
                "beneficiaryDetails" => $beneficiaryDetails,
                "personalEmail" => $personalEmail,
                "institutionalEmail" => $institutionalEmail,
                "status" => "Applicant",

                "showMissingApplicationSubmissionBillingChargeNotification" =>
                $showMissingApplicationSubmissionBillingChargeNotification,

                "showMissingApplicationAmendmentBillingChargeNotification" =>
                $showMissingApplicationAmendmentBillingChargeNotification,

                "showApplicantSubmissionPaymentForm" =>
                $showApplicantSubmissionPaymentForm,

                "showApplicantAmendmentPaymentForm" =>
                $showApplicantAmendmentPaymentForm,

                "applicantSubmissionPaymentForm" =>
                $applicantSubmissionPaymentForm,

                "applicantAmendmentPaymentForm" =>
                $applicantAmendmentPaymentForm,

                "paymentMethods" => $paymentMethods,
                "dataProvider" => $receiptsDataProvider,
                "applicationDetails" => $applicationDetails,
            ]
        );
    }

    public function actionAbandonedApplicantProfile($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        return $this->render(
            "abandoned-applicant-profile",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "status" => "Applicant's Application Removed"
            ]
        );
    }

    public function actionIncompleteApplicantProfile($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        return $this->render(
            "incomplete-applicant-profile",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "status" => "Applicant's Application Incomplete"
            ]
        );
    }


    public function actionRedirectToCustomerProfile($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userClassification = UserModel::getUserClassification($customer);

        if ($userClassification == "Student") {
            return $this->redirect([
                "student-profile",
                "username" => $customer->username
            ]);
        } elseif ($userClassification == "Successful Applicant") {
            return $this->redirect([
                "successful-applicant-profile",
                "username" => $customer->username
            ]);
        } elseif ($userClassification == "Completed Applicant") {
            return $this->redirect([
                "completed-applicant-profile",
                "username" => $customer->username
            ]);
        } elseif ($userClassification == "Abandoned Applicant") {
            return $this->redirect([
                "abandoned-applicant-profile",
                "username" => $customer->username
            ]);
        } elseif ($userClassification == "Incomplete Applicant") {
            return $this->redirect([
                "incomplete-applicant-profile",
                "username" => $customer->username
            ]);
        }
    }
}
