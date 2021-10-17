<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use common\models\PaymentMethod;
use common\models\PaymentMethodModel;
use yii\data\ArrayDataProvider;

class PaymentMethodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $paymentMethods = PaymentMethodModel::getActivePaymentMethods();

        $dataProvider =
            new ArrayDataProvider(
                [
                    "allModels" =>
                    PaymentMethodModel::prepareFormattedPaymentMethodsListing(
                        $paymentMethods
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
        $model = new PaymentMethod();

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Payment method addition successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render("create", ["model" => $model]);
    }


    public function actionUpdate($id)
    {
        $model = PaymentMethodModel::getPaymentMethodByID($id);

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true && $model->save() == true) {
                Yii::$app->getSession()->setFlash(
                    "success",
                    "Payment method modification successful."
                );
                return $this->redirect(["index"]);
            }
        }

        return $this->render("update", ["model" => $model]);
    }


    public function actionView($id)
    {
        $model = PaymentMethodModel::getPaymentMethodByID($id);
        return $this->render("view", ["model" => $model]);
    }


    public function actionDelete($id)
    {
        $paymentMethod = PaymentMethodModel::getPaymentMethodByID($id);

        if (PaymentMethodModel::deletePaymentMethod($paymentMethod) == true) {
            Yii::$app->getSession()->setFlash(
                "success",
                "{$paymentMethod->name} removal successful."
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
