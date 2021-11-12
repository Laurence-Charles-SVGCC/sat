<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use common\models\AcademicOfferingModel;
use common\models\ApplicationPeriodModel;
use common\models\AuthorizationModel;
use common\models\BillingCharge;
use common\models\BillingChargeForm;
use common\models\BillingChargeModel;
use common\models\BillingTypeModel;
use common\models\ErrorObject;
use common\models\ProgrammeFeeForm;


class StudentFeesController extends \yii\web\Controller
{
    public function actionViewFeeListing()
    {
        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingChargeModel::prepareStudentFeeApplicationPeriodCatalog(),
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


    public function actionViewAcademicOfferingFeeListing(
        $applicationPeriodId,
        $academicOfferingId
    ) {
        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $applicationPeriodId
            );

        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $academicOfferingId
            );

        $programmeName =
            AcademicOfferingModel::getProgrammeName($academicOffering);

        $title = "{$applicationPeriod->name} {$programmeName} Programme Fees";

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    // BillingChargeModel::prepareAcademicOfferingFeeCatalog(
                    //     $applicationPeriodId
                    // ),
                    BillingChargeModel::prepareAcademicOfferingFeeCatalog(
                        $academicOffering
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["billingType" => SORT_ASC],
                        "attributes" => ["billingType"]
                    ]
                ]
            );

        return $this->render(
            "view-academic-offering-fee-listing",
            [
                "dataProvider" => $dataProvider,
                "title" => $title,
                "applicationPeriodId" => $applicationPeriodId,
                "academicOfferingId" => $academicOfferingId
            ]
        );
    }


    public function actionAddFeeToAcademicOffering(
        $applicationPeriodId,
        $academicOfferingId
    ) {
        $user = Yii::$app->user->identity;
        $model = new ProgrammeFeeForm();

        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $applicationPeriodId
            );

        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID($academicOfferingId);

        $programmeName =
            AcademicOfferingModel::getProgrammeName($academicOffering);

        $programmeTitle =
            "{$applicationPeriod->name} {$programmeName} Programme Fees";

        $billingTypes =
            ArrayHelper::map(
                BillingTypeModel::getBillingTypeOptionsForAcademicOffering(
                    $academicOfferingId
                ),
                "id",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if (
                $model->load($postData) == true
                && $model->generateBillingCharge(
                    $applicationPeriodId,
                    $academicOfferingId,
                    $user->personid
                ) == true
            ) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Fee addition successful."
                );
                return $this->redirect([
                    "view-academic-offering-fee-listing",
                    "applicationPeriodId" => $applicationPeriodId,
                    "academicOfferingId" => $academicOfferingId,
                ]);
            } else {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Error ocurred generating programme fee."
                );
            }
        }

        return $this->render(
            "add-fee-to-academic-offering",
            [
                "model" => $model,
                "programmeTitle" => $programmeTitle,
                "billingTypes" => $billingTypes,
                "academicOfferingId" => $academicOfferingId,
                "applicationPeriodId" => $applicationPeriodId,
            ]
        );
    }


    public function actionUpdateAcademicOfferingFee($billingChargeId)
    {
        $user = Yii::$app->user->identity;
        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $newBillingCharge = new BillingCharge();

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $oldBillingCharge->billing_type_id
            );

        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $oldBillingCharge->application_period_id
            );
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $oldBillingCharge->academic_offering_id
            );

        $programmeName =
            AcademicOfferingModel::getProgrammeName($academicOffering);

        $programmeTitle =
            "{$applicationPeriod->name} {$programmeName} Programme Fees";

        if ($postData = Yii::$app->request->post()) {
            if ($newBillingCharge->load($postData) == true) {
                if (BillingChargeModel::generateUpdatedBillingCharge(
                    $oldBillingCharge,
                    $newBillingCharge,
                    $user->personid
                ) == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Fee modification successful."
                    );

                    return $this->redirect(
                        [
                            "view-academic-offering-fee-listing",
                            "applicationPeriodId" => $oldBillingCharge->application_period_id,
                            "academicOfferingId" => $oldBillingCharge->academic_offering_id
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        "Fee charge modification failed."
                    );
                }
            }
        }

        return $this->render(
            "update-academic-offering-fee",
            [
                "model" => $newBillingCharge,
                "feeName" => $billingType->name,
                "programmeTitle" => $programmeTitle,
                "academicOfferingId" => $oldBillingCharge->academic_offering_id,
                "applicationPeriodId" => $oldBillingCharge->application_period_id,
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
                "Fee modification successful."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Fee modification failed."
            );
        }

        $billingCharge =
            BillingChargeModel::getBillingChargeById($fromBillingChargeId);

        return $this->redirect(
            [
                "view-academic-offering-fee-listing",
                "applicationPeriodId" => $billingCharge->application_period_id,
                "academicOfferingId" => $billingCharge->academic_offering_id
            ]
        );
    }


    // public function actionUpdateBillingChargeCostFromDashboard($billingChargeId)
    // {
    //     $user = Yii::$app->user->identity;

    //     $oldBillingCharge =
    //         BillingChargeModel::getBillingChargeById($billingChargeId);

    //     $newBillingCharge = new BillingCharge();

    //     $billingType =
    //         BillingTypeModel::getBillingTypeByID(
    //             $oldBillingCharge->billing_type_id
    //         );

    //     $applicationPeriod =
    //         ApplicationPeriodModel::getApplicationPeriodByID(
    //             $oldBillingCharge->application_period_id
    //         );

    //     $academicOffering =
    //         AcademicOfferingModel::getAcademicOfferingByID(
    //             $oldBillingCharge->academic_offering_id
    //         );

    //     $programmeName =
    //         AcademicOfferingModel::getProgrammeName($academicOffering);

    //     $programmeTitle =
    //         "{$applicationPeriod->name} {$programmeName} Programme Fees";

    //     if ($postData = Yii::$app->request->post()) {
    //         if ($newBillingCharge->load($postData) == true) {
    //             if (BillingChargeModel::generateUpdatedBillingCharge(
    //                 $oldBillingCharge,
    //                 $newBillingCharge,
    //                 $user->personid
    //             ) == true) {
    //                 Yii::$app->getSession()->setFlash(
    //                     "success",
    //                     "Fee modification successful."
    //                 );

    //                 return $this->redirect(
    //                     [
    //                         "view-application-period-student-fee-dashboard",

    //                         "applicationPeriodId" =>
    //                         $oldBillingCharge->application_period_id
    //                     ]
    //                 );
    //             } else {
    //                 Yii::$app->getSession()->setFlash(
    //                     "warning",
    //                     "Fee charge modification failed."
    //                 );
    //             }
    //         }
    //     }

    //     return $this->render(
    //         "update-academic-offering-fee",
    //         [
    //             "model" => $newBillingCharge,
    //             "feeName" => $billingType->name,
    //             "programmeTitle" => $programmeTitle,
    //             "academicOfferingId" => $oldBillingCharge->academic_offering_id,
    //             "applicationPeriodId" => $oldBillingCharge->application_period_id,
    //         ]
    //     );
    // }

    public function actionUpdateAcademicOfferingBillingChargeFromDashboard(
        $billingChargeId
    ) {
        $user = Yii::$app->user->identity;

        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $newBillingCharge = new BillingCharge();

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $oldBillingCharge->billing_type_id
            );

        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $oldBillingCharge->application_period_id
            );

        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $oldBillingCharge->academic_offering_id
            );

        $programmeName =
            AcademicOfferingModel::getProgrammeName($academicOffering);

        $programmeTitle =
            "{$applicationPeriod->name} {$programmeName} Programme Fees";

        if ($postData = Yii::$app->request->post()) {
            if ($newBillingCharge->load($postData) == true) {
                if (BillingChargeModel::generateUpdatedBillingCharge(
                    $oldBillingCharge,
                    $newBillingCharge,
                    $user->personid
                ) == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Fee modification successful."
                    );

                    return $this->redirect(
                        [
                            "view-application-period-student-fee-dashboard",

                            "applicationPeriodId" =>
                            $oldBillingCharge->application_period_id
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        "Fee charge modification failed."
                    );
                }
            }
        }

        return $this->render(
            "update-academic-offering-fee",
            [
                "model" => $newBillingCharge,
                "feeName" => $billingType->name,
                "programmeTitle" => $programmeTitle,
                "academicOfferingId" => $oldBillingCharge->academic_offering_id,
                "applicationPeriodId" => $oldBillingCharge->application_period_id,
            ]
        );
    }


    public function actionUpdateApplicationPeriodBillingChargeFromDashboard($billingChargeId)
    {
        $user = Yii::$app->user->identity;

        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $newBillingCharge = new BillingCharge();

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $oldBillingCharge->billing_type_id
            );

        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $oldBillingCharge->application_period_id
            );

        $applicationPeriodTitle = "{$applicationPeriod->name} Fees";

        if ($postData = Yii::$app->request->post()) {
            if ($newBillingCharge->load($postData) == true) {
                if (BillingChargeModel::generateUpdatedBillingCharge(
                    $oldBillingCharge,
                    $newBillingCharge,
                    $user->personid
                ) == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Fee modification successful."
                    );

                    return $this->redirect(
                        [
                            "view-application-period-student-fee-dashboard",

                            "applicationPeriodId" =>
                            $oldBillingCharge->application_period_id
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "warning",
                        "Fee charge modification failed."
                    );
                }
            }
        }

        return $this->render(
            "update-application-period-fee",
            [
                "model" => $newBillingCharge,
                "feeName" => $billingType->name,
                "applicationPeriodTitle" => $applicationPeriodTitle,
                "applicationPeriodId" => $oldBillingCharge->application_period_id,
            ]
        );
    }


    public function actionUpdateBillingChargeCostFromDashboard($billingChargeId)
    {
        $oldBillingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        if ($oldBillingCharge->academic_offering_id == NULL) {
            return $this->redirect(
                [
                    "update-application-period-billing-charge-from-dashboard",
                    "billingChargeId" => $billingChargeId
                ]
            );
        } else {
            return $this->redirect(
                [
                    "update-academic-offering-billing-charge-from-dashboard",
                    "billingChargeId" => $billingChargeId
                ]
            );
        }
    }


    public function actionRevertBillingChargeFromDashboard(
        $fromBillingChargeId,
        $toBillingChargeId
    ) {
        if (BillingChargeModel::revertBillingCharge(
            $fromBillingChargeId,
            $toBillingChargeId
        ) == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "Fee modification successful."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Fee modification failed."
            );
        }

        $billingCharge =
            BillingChargeModel::getBillingChargeById($fromBillingChargeId);

        return $this->redirect(
            [
                "view-application-period-student-fee-dashboard",
                "applicationPeriodId" => $billingCharge->application_period_id
            ]
        );
    }


    public function actionViewApplicationPeriodStudentFeeDashboard(
        $applicationPeriodId
    ) {
        $user = Yii::$app->user->identity;

        $period =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $applicationPeriodId
            );

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingChargeModel::prepareApplicationPeriodStudentFeeBillingChargesCatalog(
                        $period
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["billingTypeName" => SORT_ASC],
                        "attributes" => ["billingTypeName", "programme"]
                    ]
                ]
            );

        if ($dataProvider->count > 0) {
            $hasExistingFees = true;
        } else {
            $hasExistingFees = false;
        }

        $forms = array();
        for ($i = 0; $i < 20; $i++) {
            $forms[] = new BillingChargeForm();
        }

        $studentBillingTypes =
            BillingTypeModel::getStudentBillingTypesByDivision(
                $period->divisionid
            );

        $billingTypes = ArrayHelper::map($studentBillingTypes, 'id', 'name');

        $programmes =
            ApplicationPeriodModel::generateProgrammeDropdownList(
                $applicationPeriodId
            );

        if ($postData = Yii::$app->request->post()) {
            if (Model::loadMultiple($forms, $postData) == true) {
                $feedback =
                    BillingChargeModel::generateBillingCharges(
                        $applicationPeriodId,
                        $forms,
                        $user->personid
                    );

                if ($feedback instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "danger",
                        "{$feedback->getMessage()}"
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        'Fees added successfully.'
                    );
                    return $this->redirect([
                        "view-application-period-student-fee-dashboard",
                        "applicationPeriodId" => $applicationPeriodId
                    ]);
                }
            }
        }

        return $this->render(
            "application-period-student-fee-dashboard",
            [
                "periodName" => $period->name,
                "dataProvider" => $dataProvider,
                "hasExistingFees" => $hasExistingFees,

                "displayExistingFees" =>
                $hasExistingFees ? "style=display:block" : "style=display:none",

                "displayForm" =>
                $hasExistingFees ? "style=display:none" : "style=display:block",

                "forms" => $forms,
                "billingTypes" => $billingTypes,
                "programmes" => $programmes,
            ]
        );
    }

    public function actionUpdatePayableStatus($billingChargeId, $input)
    {
        $billingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $billingCharge->payable_on_enrollment = $input;
        $billingCharge->save();

        return $this->redirect([
            "view-application-period-student-fee-dashboard",
            "applicationPeriodId" => $billingCharge->application_period_id
        ]);
    }
}
