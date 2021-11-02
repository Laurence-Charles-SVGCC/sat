<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\BillingsByDateSearchForm;
use common\models\BillingModel;
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
}
