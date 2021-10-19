<?php

namespace common\models;

class BillingScopeModel
{
    public static function getAllBillingScopes()
    {
        return BillingScope::find()->all();
    }


    public static function formatBillingScopeDetailsIntoAssociativeArray(
        $billingScope
    ) {
        $data = array();
        if ($billingScope != null) {
            $data['id'] = $billingScope->id;
            $data['name'] = $billingScope->name;
            $data['is_deleted'] = $billingScope->is_deleted;
            $data['canDeleteBillingScope'] =
                self::canDeleteBillingScope($billingScope);
        }
        return $data;
    }


    public static function prepareFormattedBillingScopesListing($billingScopes)
    {
        $data = array();
        if ($billingScopes == true) {
            foreach ($billingScopes as $billingScope) {
                $data[] =
                    self::formatBillingScopeDetailsIntoAssociativeArray($billingScope);
            }
        }

        return $data;
    }


    public static function getBillingScopeByID($id)
    {
        return BillingScope::find()->where(["id" => $id])->one();
    }


    public static function canDeleteBillingScope($billingScope)
    {
        if (
            $billingScope == true
            && $billingScope->getBillingCategories()->all() == false
            && $billingScope->is_deleted == 0
        ) {
            return true;
        }
        return false;
    }


    public static function deleteBillingScope($billingScope)
    {
        if (self::canDeleteBillingScope($billingScope) == true) {
            $billingScope->is_deleted = 1;
            return $billingScope->save();
        }
        return false;
    }


    public static function getActiveBillingScopes()
    {
        return BillingScope::find()->where(["is_deleted" => 0])->all();
    }
}
