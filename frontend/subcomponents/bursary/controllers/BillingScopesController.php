<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\BillingScope;
use common\models\BillingScopeModel;
use yii\data\ArrayDataProvider;

class BillingScopesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $billingScopes = BillingScopeModel::getActiveBillingScopes();

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingScopeModel::prepareFormattedBillingScopesListing(
                        $billingScopes
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["name" => SORT_ASC],
                        "attributes" => ["name"]
                    ]
                ]
            );

        return $this->render("index", ["dataProvider" => $dataProvider]);
    }


    public function actionCreate()
    {
        $model = new BillingScope();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Billing scope addition successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render("create", ["model" => $model]);
    }


    public function actionUpdate($id)
    {
        $model = BillingScopeModel::getBillingScopeByID($id);

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Billing scope modification successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render("update", ["model" => $model]);
    }


    public function actionView($id)
    {
        $model = BillingScopeModel::getBillingScopeByID($id);
        return $this->render("view", ["model" => $model]);
    }


    public function actionDelete($id)
    {
        $billingScope = BillingScopeModel::getBillingScopeByID($id);

        if (BillingScopeModel::deleteBillingScope($billingScope) == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "{$billingScope->name} removal successful."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Removal failed."
            );
        }

        return $this->redirect(["index"]);
    }
}
