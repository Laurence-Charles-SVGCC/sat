<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\AuthorizationModel;
use common\models\ApplicantModel;
use common\models\ApplicationModel;
use common\models\ApplicationAmendmentPaymentForm;
use common\models\ApplicationSubmissionPaymentForm;
use common\models\BatchStudentFeePaymentForm;
use common\models\BillingChargeModel;
use common\models\BillingModel;
use common\models\BillingTypeModel;
use common\models\ErrorObject;
use common\models\PaymentMethodModel;
use common\models\ReceiptModel;
use common\models\SingleStudentFeePaymentForm;
use common\models\UserModel;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class SuccessfulApplicantPaymentsController extends \yii\web\Controller
{
    public function actionAddApplicationPayment($username)
    {
        $customer = UserModel::getUserByUsername($username);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);

        $targetApplicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $activeApplicationSubmissionBillingCharge =
            BillingChargeModel::getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
                $targetApplicationPeriodId
            );

        $activeApplicationAmendmentBillingCharge =
            BillingChargeModel::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                $targetApplicationPeriodId
            );

        $applicantApplicationSubmissionBilling =
            BillingModel::getApplicantApplicationSubmissionBilling($applicant);

        if (
            $activeApplicationSubmissionBillingCharge == false
            && $activeApplicationAmendmentBillingCharge == false
        ) {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Application fees for the application period corresponding to"
                    . "applicant's submission does not exist. Please contact Bursar."
            );
            return $this->redirect(
                [
                    "profiles/redirect-to-customer-profile",
                    "username" => $username
                ]
            );
        } elseif (
            $activeApplicationSubmissionBillingCharge == false
            && $activeApplicationAmendmentBillingCharge == true
        ) {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Application submission fees for the application period"
                    . "corresponding to applicant's submission does not exist."
                    . "Please contact Bursar."
            );
            return $this->redirect(
                [
                    "profiles/redirect-to-customer-profile",
                    "username" => $username
                ]
            );
        } elseif (
            $activeApplicationSubmissionBillingCharge == true
            && $activeApplicationAmendmentBillingCharge == false
        ) {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Application amendment fees for the application period"
                    . "corresponding to applicant's submission does not exist."
                    . "Please contact Bursar."
            );
            return $this->redirect(
                [
                    "profiles/redirect-to-customer-profile",
                    "username" => $username
                ]
            );
        } elseif (
            $activeApplicationSubmissionBillingCharge == true
            && $activeApplicationAmendmentBillingCharge == true
            && $applicantApplicationSubmissionBilling == false
        ) {
            return $this->redirect(
                [
                    "add-application-submission-payment",
                    "username" => $username,
                    "applicationPeriodId" => $targetApplicationPeriodId,
                    "billingChargeId" => $activeApplicationSubmissionBillingCharge->id
                ]
            );
        } elseif (
            $activeApplicationSubmissionBillingCharge == true
            && $activeApplicationAmendmentBillingCharge == true
            && $applicantApplicationSubmissionBilling == true
        ) {
            return $this->redirect(
                [
                    "add-application-amendment-payment",
                    "username" => $username,
                    "applicationPeriodId" => $targetApplicationPeriodId,
                    "billingChargeId" => $activeApplicationAmendmentBillingCharge->id
                ]
            );
        }
    }


    public function actionAddApplicationSubmissionPayment(
        $username,
        $applicationPeriodId,
        $billingChargeId
    ) {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        $submissionBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $applicantSubmissionPaymentForm = new ApplicationSubmissionPaymentForm();
        $applicantSubmissionPaymentForm->customerId = $customer->personid;

        $applicantSubmissionPaymentForm->applicationPeriodId =
            $applicationPeriodId;

        $applicantSubmissionPaymentForm->billingChargeId = $billingChargeId;
        $applicantSubmissionPaymentForm->username = $username;
        $applicantSubmissionPaymentForm->fullName = $userFullname;
        $applicantSubmissionPaymentForm->autoPublish = 0;
        $applicantSubmissionPaymentForm->amount = $submissionBillingCharge->cost;

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if ($applicantSubmissionPaymentForm->load($postData) == true) {
                $receipt =
                    $applicantSubmissionPaymentForm->processPaymentRequest(
                        $customer->personid,
                        $user->personid,
                        $submissionBillingCharge->cost,
                        $this
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect(
                        [
                            "profiles/redirect-to-customer-profile",
                            "username" => $username
                        ]
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
        }

        return $this->render(
            "add-application-submission-payment",
            [
                "username" => $username,
                "userFullname" => $userFullname,
                "paymentMethods" => $paymentMethods,

                "applicantSubmissionPaymentForm" =>
                $applicantSubmissionPaymentForm
            ]
        );
    }


    public function actionAddApplicationAmendmentPayment(
        $username,
        $applicationPeriodId,
        $billingChargeId
    ) {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        $applicationAmendmentBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $applicantAmendmentPaymentForm = new ApplicationAmendmentPaymentForm();
        $applicantAmendmentPaymentForm->customerId = $customer->personid;

        $applicantAmendmentPaymentForm->applicationPeriodId =
            $applicationPeriodId;

        $applicantAmendmentPaymentForm->billingChargeId = $billingChargeId;
        $applicantAmendmentPaymentForm->username = $username;
        $applicantAmendmentPaymentForm->fullName = $userFullname;
        $applicantAmendmentPaymentForm->autoPublish = 0;

        $applicantAmendmentPaymentForm->amount =
            $applicationAmendmentBillingCharge->cost;

        if ($postData = Yii::$app->request->post()) {
            if ($applicantAmendmentPaymentForm->load($postData) == true) {
                $receipt =
                    $applicantAmendmentPaymentForm->processPaymentRequest(
                        $customer->personid,
                        $user->personid,
                        $applicationAmendmentBillingCharge->cost,
                        $this
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect(
                        [
                            "profiles/redirect-to-customer-profile",
                            "username" => $username
                        ]
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
        }

        return $this->render(
            "add-application-amendment-payment",
            [
                "applicantAmendmentPaymentForm" =>
                $applicantAmendmentPaymentForm,

                "username" => $username,
                "paymentMethods" => $paymentMethods,
                "userFullname" => $userFullname,
            ]
        );
    }


    public function actionEnrollmentPaymentsReport($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);

        $applicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $batchStudentFeePaymentForm = new BatchStudentFeePaymentForm();

        $batchStudentFeePaymentForm->fillModel(
            $customer,
            $user->personid,
            $userFullname,
            $applicationPeriodId
        );

        $successfulApplication =
            ApplicationModel::getSuccessfulApplication($applicant);

        $batchStudentFeePaymentBillingForms =
            $batchStudentFeePaymentForm->generateDefaultBillingFormsForSuccessfulApplicant(
                $successfulApplication->academicoffering
            );

        if (empty($batchStudentFeePaymentBillingForms)) {
            $outstandingFeesExist = false;
        } else {
            $outstandingFeesExist = true;
        }

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        $feesDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    ApplicantModel::prepareSuccessfulApplicantFeeReport(
                        $customer->personid,
                        $successfulApplication->academicoffering
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["billingTypeName" => SORT_ASC],
                        "attributes" => ["billingTypeName", "programme"]
                    ]
                ]
            );

        if ($postData = Yii::$app->request->post()) {
            if (
                $batchStudentFeePaymentForm->load($postData) == true
                && Model::loadMultiple(
                    $batchStudentFeePaymentBillingForms,
                    $postData
                )
                == true
            ) {
                $receipt =
                    $batchStudentFeePaymentForm->processSuccessfulApplicantPaymentRequest(
                        $batchStudentFeePaymentBillingForms,
                        $this
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "preview-receipt",
                        "receiptId" => $receipt->id
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred load payment"
                );
            }
        }

        return $this->render(
            "enrollment-payments-report",
            [
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "dataProvider" => $feesDataProvider,
                "batchStudentFeePaymentForm" => $batchStudentFeePaymentForm,
                "paymentMethods" => $paymentMethods,

                "batchStudentFeePaymentBillingForms" =>
                $batchStudentFeePaymentBillingForms,

                "outstandingFeesExist" => $outstandingFeesExist,
            ]
        );
    }


    public function actionMakeFeePayment($username, $billingChargeId)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        $billingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $billingCharge->billing_type_id
            );

        $model = new SingleStudentFeePaymentForm();
        $model->fillModel($customer, $userFullname, $billingCharge);

        $paymentMethods =
            ArrayHelper::map(
                PaymentMethodModel::getActivePaymentMethods(),
                "paymentmethodid",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $receipt =
                    $model->processIndividualBillingPaymentRequest(
                        $user->personid
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "preview-receipt",
                        "receiptId" => $receipt->id
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
        }

        return $this->render(
            "make-fee-payment",
            [
                "model" => $model,
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "fee" => $billingType->name,
                "paymentMethods" => $paymentMethods
            ]
        );
    }


    public function actionPreviewReceipt($receiptId)
    {
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;
        $total = number_format(ReceiptModel::calculateReceiptTotal($receipt), 2);

        return $this->render(
            "preview-receipt",
            [
                "receipt" => $receipt,
                "billings" => $billings,
                "total" => $total,
                "applicantName" => $applicantName,
                "applicantId" => $applicantId
            ]
        );
    }


    public function actionRedoReceipt($receiptId)
    {
        $user = Yii::$app->user->identity;
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;

        if (ReceiptModel::deleteReceipt(
            $receipt,
            $billings,
            $user->personid
        ) == true) {
            return $this->redirect([
                "enrollment-payments-report",
                "username" => $applicantId
            ]);
        } else {
            Yii::$app->getSession()->setFlash(
                'warning',
                'Error occurred deleting receipt.'
            );
        }
        return $this->redirect([
            "view-receipt",
            "id" => $receiptId,
            "username" => UserModel::getUserById($receipt->customer_id)->username,
        ]);
    }


    public function actionApproveAndPublishReceipt($receiptId)
    {
        $receipt = ReceiptModel::getReceiptById($receiptId);
        $billings = ReceiptModel::getBillings($receipt);
        $customer = UserModel::getUserById($receipt->customer_id);
        $applicantName = UserModel::getUserFullname($customer);
        $applicantId = $customer->username;

        ReceiptModel::publishReceipt(
            $this,
            $receipt,
            $billings,
            $applicantName,
            $applicantId
        );

        $receipt->publish_count += 1;
        if ($receipt->save() == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "Receipt published successfully."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Error occurred publishing receipt."
            );
        }

        return $this->redirect([
            "payments/view-receipt",
            "id" => $receiptId,
            "username" => UserModel::getUserById($receipt->customer_id)->username
        ]);
    }
}
