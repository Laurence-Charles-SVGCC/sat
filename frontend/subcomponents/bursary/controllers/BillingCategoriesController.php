<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\BillingCategory;
use common\models\BillingCategoryModel;
use common\models\BillingScopeModel;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class BillingCategoriesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $billingCategories = BillingCategoryModel::getActiveBillingCategories();

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingCategoryModel::prepareFormattedBillingCategoriesListing(
                        $billingCategories
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["name" => SORT_ASC],
                        "attributes" => ["name", "billingScope"]
                    ]
                ]
            );

        return $this->render("index", ["dataProvider" => $dataProvider]);
    }


    public function actionCreate()
    {
        $model = new BillingCategory();

        $billingScopes =
            ArrayHelper::map(
                BillingScopeModel::getActiveBillingScopes(),
                "id",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Billing category addition successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render(
            "create",
            ["model" => $model, "billingScopes" => $billingScopes]
        );
    }


    public function actionUpdate($id)
    {
        $model = BillingCategoryModel::getBillingCategoryByID($id);

        $billingScopes =
            ArrayHelper::map(
                BillingScopeModel::getActiveBillingScopes(),
                "id",
                "name"
            );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Billing category modification successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render(
            "update",
            ["model" => $model, "billingScopes" => $billingScopes]
        );
    }


    public function actionView($id)
    {
        $model = BillingCategoryModel::getBillingCategoryByID($id);

        $modelScope =
            BillingScopeModel::getBillingScopeByID($model->billing_scope_id);

        return $this->render(
            "view",
            [
                "model" => $model,
                "modelScope" => $modelScope->name
            ]
        );
    }


    public function actionDelete($id)
    {
        $billingCategory = BillingCategoryModel::getBillingCategoryByID($id);

        if (
            BillingCategoryModel::deleteBillingCategory($billingCategory)
            == true
        ) {
            Yii::$app->getSession()->setFlash(
                "success",
                "{$billingCategory->name} removal successful."
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
