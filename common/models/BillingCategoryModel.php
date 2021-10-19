<?php

namespace common\models;

class BillingCategoryModel
{
    public static function getAllBillingCategories()
    {
        return BillingCategory::find()->all();
    }


    public static function formatBillingCategoryDetailsIntoAssociativeArray(
        $billingCategory
    ) {
        $data = array();
        if ($billingCategory != null) {
            $data["id"] = $billingCategory->id;
            $data["name"] = $billingCategory->name;
            $data["billingScopeID"] = $billingCategory->billing_scope_id;

            $billingScope =
                BillingScopeModel::getBillingScopeByID(
                    $billingCategory->billing_scope_id
                );

            $data["billingScope"] = $billingScope->name;
            $data["is_deleted"] = $billingCategory->is_deleted;

            $data["canDeleteBillingCategory"] =
                self::canDeleteBillingCategory($billingCategory);
        }
        return $data;
    }


    public static function prepareFormattedBillingCategoriesListing(
        $billingCategories
    ) {
        $data = array();
        if ($billingCategories == true) {
            foreach ($billingCategories as $billingCategory) {
                $data[] =
                    self::formatBillingCategoryDetailsIntoAssociativeArray(
                        $billingCategory
                    );
            }
        }
        return $data;
    }


    public static function getBillingCategoryByID($id)
    {
        return BillingCategory::find()->where(["id" => $id])->one();
    }


    public static function canDeleteBillingCategory($billingCategory)
    {
        if (
            $billingCategory == true
            && $billingCategory->getBillingTypes()->all() == false
            && $billingCategory->is_deleted == 0
        ) {
            return true;
        }
        return false;
    }


    public static function deleteBillingCategory($billingCategory)
    {
        if (self::canDeleteBillingCategory($billingCategory) == true) {
            $billingCategory->is_deleted = 1;
            return $billingCategory->save();
        }
        return false;
    }


    public static function getActiveBillingCategories()
    {
        return BillingCategory::find()->where(["is_deleted" => 0])->all();
    }
}
