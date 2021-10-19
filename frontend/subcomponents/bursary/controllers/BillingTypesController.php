<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\base\Model;
use common\models\BillingCategoryModel;
use common\models\BillingScopeModel;
use common\models\BillingTypeBatchForm;
use common\models\BillingTypeForm;
use common\models\BillingTypeModel;
use common\models\DivisionModel;
use common\models\ErrorObject;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class BillingTypesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $billingTypes = BillingTypeModel::getActiveBillingTypes();

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    BillingTypeModel::prepareFormattedBillingTypesListing(
                        $billingTypes
                    ),
                    "pagination" => ["pageSize" => 100],
                    "sort" => [
                        "defaultOrder" => ["name" => SORT_ASC],
                        "attributes" => ["name", "billingCategory"]
                    ]
                ]
            );

        return $this->render("index", ["dataProvider" => $dataProvider]);
    }


    public function actionCreate()
    {
        $model = new BillingTypeForm();

        $billingCategories =
            ArrayHelper::map(
                BillingCategoryModel::getActiveBillingCategories(),
                "id",
                "name"
            );

        $divisions = ArrayHelper::map(
            DivisionModel::getMainDivisions(),
            "divisionid",
            "abbreviation"
        );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                if ($model->generateBillingTypeModel() == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Billing type addition successful."
                    );
                    return $this->redirect(["index"]);
                } else {
                    Yii::$app->getSession()->setFlash(
                        "error",
                        "Billing type must be administered by at least one division."
                    );
                }
            }
        }

        return $this->render(
            "create",
            [
                "model" => $model,
                "billingCategories" => $billingCategories,
                "divisions" => $divisions
            ]
        );
    }


    public function actionUpdate($id)
    {
        $model = BillingTypeModel::getBillingTypeByID($id);

        $billingCategories =
            ArrayHelper::map(
                BillingCategoryModel::getActiveBillingCategories(),
                "id",
                "name"
            );

        $divisions = ArrayHelper::map(
            DivisionModel::getMainDivisions(),
            "divisionid",
            "abbreviation"
        );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Billing type modification successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render(
            "update",
            [
                "model" => $model,
                "billingCategories" => $billingCategories,
                "divisions" => $divisions
            ]
        );
    }


    public function actionView($id)
    {
        $model = BillingTypeModel::getBillingTypeByID($id);

        $modelCategory =
            BillingCategoryModel::getBillingCategoryByID(
                $model->billing_category_id
            );

        $modelScope =
            BillingScopeModel::getBillingScopeByID(
                $modelCategory->billing_scope_id
            );

        return $this->render(
            "view",
            [
                "model" => $model,
                "modelCategory" => $modelCategory->name,
                "modelScope" => $modelScope->name
            ]
        );
    }


    public function actionDelete($id)
    {
        $billingType = BillingTypeModel::getBillingTypeByID($id);

        if (BillingTypeModel::deleteBillingType($billingType) == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "{$billingType->name} removal successful."
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "warning",
                "Removal failed."
            );
        }

        return $this->redirect(["index"]);
    }


    public function actionCreateMultiple()
    {
        $billingTypeBatchForms = BillingTypeBatchForm::generateBlankForms(10);

        $billingCategories =
            ArrayHelper::map(
                BillingCategoryModel::getActiveBillingCategories(),
                "id",
                "name"
            );

        $divisions = ArrayHelper::map(
            DivisionModel::getMainDivisions(),
            "divisionid",
            "abbreviation"
        );

        if ($postData = Yii::$app->request->post()) {
            if (Model::loadMultiple($billingTypeBatchForms, $postData) == true) {
                $feedback =
                    BillingTypeModel::processBillingTypeBatchForms(
                        $billingTypeBatchForms
                    );
                if ($feedback instanceof ErrorObject) {
                    Yii::$app->getSession()->setFlash(
                        "error",
                        $feedback->getMessage()
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Billing types created."
                    );
                    return $this->redirect(["index"]);
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    "error",
                    "Error processing user request."
                );
            }
        }

        return $this->render(
            "create-multiple",
            [
                "billingTypeBatchForms" => $billingTypeBatchForms,
                "billingCategories" => $billingCategories,
                "divisions" => $divisions
            ]
        );
    }
}
