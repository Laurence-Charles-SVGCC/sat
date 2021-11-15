<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\AcademicOfferingModel;
use common\models\ApplicantModel;
use common\models\ApplicationPeriod;
use common\models\ApplicationPeriodModel;
use common\models\BillingsByDateSearchForm;
use common\models\BillingModel;
use common\models\EmailModel;
use common\models\ReceiptModel;
use common\models\ReceiptsByDateSearchForm;
use yii\data\ArrayDataProvider;

class ReportsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render("index");
    }


    public function actionBillingsByDate()
    {
        $model = new BillingsByDateSearchForm();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                return $this->redirect([
                    "billings-by-date-result",
                    "startDate" => $model->startDate,
                    "endDate" => $model->endDate
                ]);
            }
        }
        return $this->render("billings-by-date", ["model" => $model]);
    }


    public function actionBillingsByDateResult($startDate, $endDate)
    {
        $model = new BillingsByDateSearchForm();
        $model->startDate = $startDate;
        $model->endDate = $endDate;
        $billings = BillingModel::getBillingsByDate($model);

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingModel::prepareFormattedBillingListing(
                        $billings
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["receiptId" => SORT_ASC],
                        "attributes" => [
                            "receiptId",
                            "paymentMethod",
                            "billingType",
                            "customerLastname"
                        ]
                    ]
                ]
            );

        return $this->render(
            "billings-by-date-search-results",
            ["dataProvider" => $dataProvider]
        );
    }


    public function actionReceiptsByDate()
    {
        $model = new ReceiptsByDateSearchForm();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                return $this->redirect([
                    "receipts-by-date-result",
                    "startDate" => $model->startDate,
                    "endDate" => $model->endDate
                ]);
            }
        }
        return $this->render("receipts-by-date", ["model" => $model]);
    }


    public function actionReceiptsByDateResult($startDate, $endDate)
    {
        $model = new ReceiptsByDateSearchForm();
        $model->startDate = $startDate;
        $model->endDate = $endDate;
        $receipts = ReceiptModel::getReceiptsByDate($model);

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    ReceiptModel::prepareFormattedReceiptReport(
                        $receipts
                    ),
                    "pagination" => ["pageSize" => 200],
                    "sort" => [
                        "defaultOrder" => ["receiptId" => SORT_ASC],
                        "attributes" => [
                            "receiptId",
                            "paymentMethod",
                            "customerLastname"
                        ]
                    ]
                ]
            );

        return $this->render(
            "receipts-by-date-search-results",
            ["dataProvider" => $dataProvider]
        );
    }


    public function actionEnrolmentPaymentsByProgramme(
        $applicationPeriodId = null,
        $academicOfferingId = null
    ) {
        $dataProvider = null;
        $data = array();
        $academicOfferings = array();
        $applicationPeriodName = null;
        $academicOfferingName = null;

        $applicationPeriods =
            ApplicationPeriod::find()
            ->where([
                "isactive" => 1,
                "isdeleted" => 0,
                "programmes_added" => 1
            ])
            ->all();

        if ($applicationPeriodId == true) {
            $applicationPeriodName =
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $applicationPeriodId
                );

            $academicOfferings =
                ApplicationPeriodModel::generateProgrammeDropdownList(
                    $applicationPeriodId
                );
        }

        if ($academicOfferingId == true) {
            $academicOffering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $academicOfferingId
                );
            $academicOfferingName =
                AcademicOfferingModel::getProgrammeName($academicOffering);

            $applications =
                AcademicOfferingModel::getSuccessfulApplications(
                    $academicOffering
                );

            if (!empty($applications)) {
                foreach ($applications as $application) {
                    $app = array();
                    $userAccount = $application->getPerson()->one();

                    $applicantAccount =
                        ApplicantModel::getApplicantByPersonid($application->personid);

                    $app['username'] = $userAccount->username;
                    $app['firstname'] = $applicantAccount->firstname;
                    $app['lastname'] = $applicantAccount->lastname;

                    $email = emailModel::getEmailByPersonid($application->personid);
                    $app["email"] = $email->email;

                    $enrolmentBillingChargesTotal =
                        ApplicantModel::getEnrolmentBillingChargesTotal($application);
                    $app['enrolmentBillingChargesTotal'] =
                        $enrolmentBillingChargesTotal;

                    $enrolmentBillingChargesPaid =
                        ApplicantModel::enrolmentBillingChargesPaid($application);
                    $app['enrolmentBillingChargesPaid'] =
                        $enrolmentBillingChargesPaid;

                    $balance =
                        $enrolmentBillingChargesTotal - $enrolmentBillingChargesPaid;
                    $app["outstandingEnrolmentBalance"] = $balance;


                    if ($balance == 0) {
                        $app["outstandingBillingCharges"] = "";
                    } else {
                        $app["outstandingBillingCharges"] =
                            ApplicantModel::getOutstandingBillingCharges($application);
                    }

                    $data[] = $app;
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 100,
                ],
                'sort' => [
                    'attributes' => [
                        'username',
                        'firstname',
                        'lastname'
                    ],
                ],
            ]);
        }

        return $this->render(
            "enrolment-payments-by-programme",
            [
                "applicationPeriods" => $applicationPeriods,
                "applicationPeriodId" => $applicationPeriodId,
                "applicationPeriodName" => $applicationPeriodName,
                "academicOfferingId" => $academicOfferingId,
                "academicOfferingName" => $academicOfferingName,
                "academicOfferings" => $academicOfferings,
                "dataProvider" => $dataProvider
            ]
        );
    }
}
