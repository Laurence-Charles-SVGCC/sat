<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\AcademicOfferingModel;
use common\models\AuthorizationModel;
use common\models\ApplicantModel;
use common\models\BatchStudentFeePaymentForm;
use common\models\BillingChargeModel;
use common\models\BillingTypeModel;
use common\models\ErrorObject;
use common\models\PaymentMethodModel;
use common\models\SingleStudentFeePaymentForm;
use common\models\StudentModel;
use common\models\StudentRegistrationModel;
use common\models\UserModel;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class StudentPaymentsController extends \yii\web\Controller
{
    public function actionScheduledFeeReport($username, $studentRegistrationId)
    {
        $user = Yii::$app->user->identity;

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $studentRegistrationId
            );

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

        $batchStudentFeePaymentBillingForms =
            $batchStudentFeePaymentForm->generateDefaultBillingFormsForStudent(
                $studentRegistration
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

        $programme =
            AcademicOfferingModel::getProgrammeNameByStudentRegistrationId(
                $studentRegistrationId
            );

        $feesDataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    StudentModel::prepareFeePaymentReportByRegistration(
                        $studentRegistration
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
                    $batchStudentFeePaymentForm->processEnrolledStudentPaymentRequest(
                        $batchStudentFeePaymentBillingForms,
                        $this,
                        $studentRegistrationId
                    );

                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                } else {
                    return $this->redirect([
                        "profiles/redirect-to-customer-profile",
                        "username" => $username
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
            "scheduled-fee-report",
            [
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "dataProvider" => $feesDataProvider,
                "batchStudentFeePaymentForm" => $batchStudentFeePaymentForm,
                "paymentMethods" => $paymentMethods,
                "programme" => $programme,
                "studentRegistrationId" => $studentRegistrationId,
                "outstandingFeesExist" => $outstandingFeesExist,

                "batchStudentFeePaymentBillingForms" =>
                $batchStudentFeePaymentBillingForms,
            ]
        );
    }


    public function actionMakeFeePayment(
        $username,
        $billingChargeId,
        $studentRegistrationId
    ) {
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
                        $user->personid,
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
                            "scheduled-fee-report",
                            "username" => $username,
                            "studentRegistrationId" =>  $studentRegistrationId
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
            "make-fee-payment",
            [
                "model" => $model,
                "userFullname" => $userFullname,
                "username" => $customer->username,
                "fee" => $billingType->name,
                "paymentMethods" => $paymentMethods,
                "studentRegistrationId" => $studentRegistrationId
            ]
        );
    }
}
