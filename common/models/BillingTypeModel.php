<?php

namespace common\models;

class BillingTypeModel
{
    public static function getAllBillingTypes()
    {
        return BillingType::find()->all();
    }


    public static function formatBillingTypeDetailsIntoAssociativeArray(
        $billingType
    ) {
        $data = array();
        if ($billingType != null) {
            $data["id"] = $billingType->id;
            $data["name"] = $billingType->name;
            $data["billingCategoryID"] = $billingType->billing_category_id;

            $billingCategory =
                BillingCategoryModel::getBillingCategoryByID(
                    $billingType->billing_category_id
                );

            $data["billingCategory"] = $billingCategory->name;

            $data["divisionID"] = $billingType->division_id;
            $division =
                DivisionModel::getDivisionByID($billingType->division_id);
            $data["division"] = $division->abbreviation;

            $data["is_deleted"] = $billingType->is_deleted;

            $data["canDeleteBillingType"] =
                self::canDeleteBillingType($billingType);
        }
        return $data;
    }


    public static function prepareFormattedBillingTypesListing($billingTypes)
    {
        $data = array();
        if ($billingTypes == true) {
            foreach ($billingTypes as $billingType) {
                $data[] =
                    self::formatBillingTypeDetailsIntoAssociativeArray(
                        $billingType
                    );
            }
        }

        return $data;
    }


    public static function getBillingTypeByID($id)
    {
        return BillingType::find()->where(["id" => $id])->one();
    }


    public static function canDeleteBillingType($billingType)
    {
        if (
            $billingType == true
            && $billingType->getBillingCharges()->all() == false
            && $billingType->is_deleted == 0
        ) {
            return true;
        } else {
            return false;
        }
    }


    public static function deleteBillingType($billingType)
    {
        if (self::canDeleteBillingType($billingType) == true) {
            $billingType->is_deleted = 1;
            return $billingType->save();
        }
        return false;
    }


    public static function getActiveBillingTypes()
    {
        return BillingType::find()->where(["is_deleted" => 0])->all();
    }


    public static function processBillingTypeBatchForms($forms)
    {
        $validBillingTypeBatchForms = array();
        foreach ($forms as $billingTypeBatchForm) {
            if ($billingTypeBatchForm->selectedButFailsValidation() == true) {
                return new ErrorObject("One of more records fails validation");
            }
            if ($billingTypeBatchForm->selectedAndPassesValidation() == true) {
                $validBillingTypeBatchForms[] = $billingTypeBatchForm;
            }
        }

        if (empty($validBillingTypeBatchForms)) {
            return new ErrorObject(
                "You must select at least one record to be saved."
            );
        }

        $billingTypes = array();
        foreach ($validBillingTypeBatchForms as $record) {
            $models = $record->generateBillingTypeModels();
            if (empty($models) == true) {
                return new ErrorObject(
                    "Error occurred generating   {$record->name} billingType."
                );
            } else {
                array_merge($billingTypes, $models);
            }
        }
        return $billingTypes;
    }


    public static function getBillingTypeByName($name)
    {
        return BillingType::find()->where(["name" => $name])->one();
    }


    public static function getStudentBillingTypes()
    {
        return BillingType::find()
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_scope.name" => "Student",
                "billing_type.is_deleted" => 0
            ])
            ->orderBy("name Asc")
            ->all();
    }


    public static function getStudentBillingTypesByDivision($divisionId)
    {
        return BillingType::find()
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_type.division_id" => $divisionId,
                "billing_scope.name" => "Student",
                "billing_type.is_deleted" => 0
            ])
            ->orderBy("name Asc")
            ->all();
    }


    public static function getActiveApplicationSubmissionBillingTypeByApplicationPeriod(
        $applicationPeriod
    ) {
        return BillingType::find()
            ->where([
                "name" => "Application Submission",
                "division_id" =>  $applicationPeriod->divisionid,
                "is_deleted" => 0
            ])
            ->one();
    }


    public static function getActiveApplicationAmendmentBillingTypeByApplicationPeriod(
        $applicationPeriod
    ) {
        return BillingType::find()
            ->where([
                "name" => "Application Amendment",
                "division_id" =>  $applicationPeriod->divisionid,
                "is_deleted" => 0
            ])
            ->one();
    }


    public static function getBillingTypeOptionsForAcademicOffering(
        $academicOfferingId
    ) {
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID($academicOfferingId);

        $applicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $academicOffering->applicationperiodid
            );

        return BillingType::find()
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_type.division_id" =>  $applicationPeriod->divisionid,
                "billing_type.is_deleted" =>  0,
                "billing_scope.name" => "Student"
            ])
            ->all();
    }
}
