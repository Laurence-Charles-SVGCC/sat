<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\ApplicationPeriodModel;
use common\models\BillingCharge;
use common\models\BillingChargeModel;
use common\models\ErrorObject;

class ApplicationFeesController extends \yii\web\Controller
{
    public function actionViewFeeListing()
    {
        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingChargeModel::prepareBillingChargesCatalog(),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["name" => SORT_ASC],
                        "attributes" => ["name"]
                    ]
                ]
            );

        return $this->render(
            "view-fee-listing",
            ["dataProvider" => $dataProvider]
        );
    }


    public function actionAddApplicationSubmissionBillingChargeToApplicationPeriod(
        $applicationPeriodId
    ) {
        $user = Yii::$app->user->identity;
        $model = new BillingCharge();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $feedback =
                    BillingChargeModel::processRequestToAddApplicationSubmissionBillingChargeToApplicationPeriod(
                        $model,
                        $applicationPeriodId,
                        $user->personid
                    );
                if ($feedback instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $feedback->getMessage()
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Application submission charge creation successful."
                    );
                    return $this->redirect(["view-fee-listing"]);
                }
            }
        }

        return $this->render(
            "add-application-submission-billing-charge-to-application-period",
            [
                "model" => $model,

                "applicationPeriodName" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $applicationPeriodId
                )
            ]
        );
    }


    public function actionEditApplicationSubmissionBillingCharge(
        $billingChargeId
    ) {
        $user = Yii::$app->user->identity;

        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $model = new BillingCharge();

        if ($postData = Yii::$app->request->post()) {
            if (
                $model->load($postData) == true
                && BillingChargeModel::generateUpdatedApplicationSubmissionBillingCharge(
                    $oldBillingCharge,
                    $model,
                    $user->personid
                ) == true
            ) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Application submission charge modification successful."
                );
                return $this->redirect(["view-fee-listing"]);
            } else {
                Yii::$app->getSession()->setFlash(
                    "warning",
                    "Application submission charge modification failed."
                );
            }
        }

        return $this->render(
            "edit-application-submission-billing-charge-to-application-period",
            [
                "model" => $model,

                "applicationPeriodName" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $model->application_period_id
                )
            ]
        );
    }


    public function actionAddApplicationAmendmentBillingChargeToApplicationPeriod(
        $applicationPeriodId
    ) {
        $user = Yii::$app->user->identity;
        $model = new BillingCharge();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                $feedback =
                    BillingChargeModel::processRequestToAddApplicationAmendmentBillingChargeToApplicationPeriod(
                        $model,
                        $applicationPeriodId,
                        $user->personid
                    );
                if ($feedback instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        $feedback->getMessage()
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Application amendment charge creation successful."
                    );
                    return $this->redirect(["view-fee-listing"]);
                }
            }
        }

        return $this->render(
            "add-application-amendment-billing-charge-to-application-period",
            [
                "model" => $model,

                "applicationPeriodName" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $applicationPeriodId
                )
            ]
        );
    }


    public function actionEditApplicationAmendmentBillingCharge(
        $billingChargeId
    ) {
        $user = Yii::$app->user->identity;
        $model = new BillingCharge();

        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                if (BillingChargeModel::generateUpdatedApplicationAmendmentBillingCharge(
                    $oldBillingCharge,
                    $model,
                    $user->personid
                ) == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Application amendment charge modification successful."
                    );
                    return $this->redirect(["view-fee-listing"]);
                } else {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        "Application amendment charge modification failed."
                    );
                }
            }
        }

        return $this->render(
            "edit-application-amendment-billing-charge-to-application-period",
            [
                "model" => $model,

                "applicationPeriodName" =>
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $model->application_period_id
                )
            ]
        );
    }


    public function actionRevertBillingCharge(
        $fromBillingChargeId,
        $toBillingChargeId
    ) {
        if (BillingChargeModel::revertBillingCharge(
            $fromBillingChargeId,
            $toBillingChargeId
        ) == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "Application submission charge modification successful."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Application submission charge modification failed."
            );
        }
        return $this->redirect(["view-fee-listing"]);
    }
}
