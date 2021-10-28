<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\ApplicantModel;
use common\models\ApplicationAmendmentPaymentForm;
use common\models\ApplicationSubmissionPaymentForm;
use common\models\BillingChargeModel;
use common\models\ErrorObject;
use common\models\UserModel;

class CompletedApplicantPaymentsController extends \yii\web\Controller
{
    public function actionProcessApplicationSubmissionPaymentForm($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);

        $applicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $submissionBillingCharge =
            BillingChargeModel::getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
                $applicationPeriodId
            );

        $model = new ApplicationSubmissionPaymentForm();
        $model->customerId = $customer->personid;
        $model->applicationPeriodId = $applicationPeriodId;
        $model->billingChargeId = $submissionBillingCharge->id;
        $model->username = $username;
        $model->fullName = $userFullname;
        $model->autoPublish = 1;
        $model->amount = $submissionBillingCharge->cost;

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $customer = UserModel::getUserByUsername($username);
                $receipt =
                    $model->processPaymentRequest(
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
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
            return $this->redirect([
                "profiles/redirect-to-customer-profile",
                "username" => $username
            ]);
        }
    }


    public function actionProcessApplicationAmendmentPaymentForm($username)
    {
        $user = Yii::$app->user->identity;
        $customer = UserModel::getUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);
        $applicant = ApplicantModel::getApplicantByPersonid($customer->personid);

        $applicationPeriodId =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        $amendmentBillingCharge =
            BillingChargeModel::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                $applicationPeriodId
            );

        $model = new ApplicationAmendmentPaymentForm();
        $model->customerId = $customer->personid;
        $model->applicationPeriodId = $applicationPeriodId;
        $model->billingChargeId = $amendmentBillingCharge->id;
        $model->username = $username;
        $model->fullName = $userFullname;
        $model->autoPublish = 1;
        $model->amount = $amendmentBillingCharge->cost;

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $customer = UserModel::getUserByUsername($username);
                $receipt = $model->processPaymentRequest(
                    $customer->personid,
                    $user->personid,
                    $amendmentBillingCharge->cost,
                    $this
                );
                if ($receipt instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $receipt->getMessage()
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Error occurred loading payment"
                );
            }
            return $this->redirect([
                "profiles/redirect-to-customer-profile",
                "username" => $username
            ]);
        }
    }
}
