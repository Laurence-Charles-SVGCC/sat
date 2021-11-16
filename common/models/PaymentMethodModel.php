<?php

namespace common\models;

use yii\helpers\ArrayHelper;

class PaymentMethodModel
{
    public static function getAllPaymentMethods()
    {
        return PaymentMethod::find()->all();
    }

    public static function getActivePaymentMethods()
    {
        return PaymentMethod::find()->where(["isdeleted" => 0])->all();
    }

    public static function generatePaymentMethodsAssociativeArray($paymentMethods)
    {
        if ($paymentMethods == false) {
            return array();
        }

        return ArrayHelper::map($paymentMethods, "paymentmethodid", "name");
    }

    public static function formatPaymentMethodDetailsIntoAssociativeArray(
        $paymentMethod
    ) {
        $data = array();
        if ($paymentMethod != null) {
            $data['paymentmethodid'] = $paymentMethod->paymentmethodid;
            $data['name'] = $paymentMethod->name;
            $data['isdeleted'] = $paymentMethod->isdeleted;
        }
        return $data;
    }

    public static function prepareFormattedPaymentMethodsListing($paymentMethods)
    {
        $data = array();
        if ($paymentMethods == true) {
            foreach ($paymentMethods as $paymentMethod) {
                $data[] =
                    self::formatPaymentMethodDetailsIntoAssociativeArray($paymentMethod);
            }
        }

        return $data;
    }

    public static function getPaymentMethodByID($paymentmethodid)
    {
        return PaymentMethod::find()->where(["paymentmethodid" => $paymentmethodid])->one();
    }

    public static function canDeletePaymentMethod($paymentMethod)
    {
        if ($paymentMethod == true) {
            if ($paymentMethod->isdeleted == 0) {
                return true;
            }
        }
        return false;
    }

    public static function deletePaymentMethod($paymentMethod)
    {
        if (self::canDeletePaymentMethod($paymentMethod) == true) {
            $paymentMethod->isdeleted = 1;
            return $paymentMethod->save();
        }
        return false;
    }

    public static function getPaymentMethodNameByID($paymentmethodid)
    {
        $paymentMethod = self::getPaymentMethodByID($paymentmethodid);
        if ($paymentMethod == true) {
            return $paymentMethod->name;
        }
        return null;
    }


    public static function getNonWaiverPaymentMethods()
    {
        return PaymentMethod::find()
            ->where(["isdeleted" => 0])
            ->andWhere(['not', ['name' => 'Vaccination Waiver']])
            ->all();
    }
}
