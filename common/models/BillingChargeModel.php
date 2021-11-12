<?php

namespace common\models;

class BillingChargeModel
{
    public static function prepareBillingChargesCatalog()
    {
        $data = array();
        $periods = ApplicationPeriodModel::getActiveApplicationPeriods();

        foreach ($periods as $period) {
            $data[] =
                self::generateBillingChargeRecordForApplicationPeriod($period);
        }
        return $data;
    }


    public static function generateBillingChargeRecordForApplicationPeriod(
        $period
    ) {
        $record = array();
        $record["applicationPeriodId"] = $period->applicationperiodid;
        $record["periodName"] =  $period->name;

        $applicationSubmissionCharge =
            self::getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
                $period->applicationperiodid
            );

        if ($applicationSubmissionCharge == true) {
            $record["applicationSubmissionChargeId"] =
                $applicationSubmissionCharge->id;

            $record["applicationSubmissionChargeCost"] =
                $applicationSubmissionCharge->cost;

            $record["otherApplicationSubmissionChargeInformation"] =
                self::getFormattedAssociativeArrayOfPastApplicationSubmissionBilllingChargesForApplicationPeriod(
                    $period->applicationperiodid
                );
        } else {
            $record["applicationSubmissionChargeId"] = null;
            $record["applicationSubmissionChargeCost"] = null;
            $record["otherApplicationSubmissionChargeInformation"] = null;
        }

        $applicationAmendmentCharge =
            self::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                $period->applicationperiodid
            );

        if ($applicationAmendmentCharge == true) {
            $record["applicationAmendmentChargeId"] =
                $applicationAmendmentCharge->id;

            $record["applicationAmendmentChargeCost"] =
                $applicationAmendmentCharge->cost;

            $record["otherApplicationAmendmentChargeInformation"] =
                self::getFormattedAssociativeArrayOfPastApplicationAmendmentBilllingChargesForApplicationPeriod(
                    $period->applicationperiodid
                );
        } else {
            $record["applicationAmendmentChargeId"] = null;
            $record["applicationAmendmentChargeCost"] = null;
            $record["otherApplicationAmendmentChargeInformation"] = null;
        }

        return $record;
    }


    public static function getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
        $applicationPeriodId
    ) {
        $billingCharges =
            BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where(
                [
                    "billing_charge.application_period_id" => $applicationPeriodId,
                    "billing_type.name" => "Application Submission",
                    "billing_charge.is_active" => 1,
                    "billing_charge.is_deleted" => 0
                ]
            )
            ->all();
        if (!empty($billingCharges)) {
            return $billingCharges[0];
        }
        return false;
    }


    public static function getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
        $applicationPeriodId
    ) {
        $billingCharges =  BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where(
                [
                    "billing_charge.application_period_id" => $applicationPeriodId,
                    "billing_type.name" => "Application Amendment",
                    "billing_charge.is_active" => 1,
                    "billing_charge.is_deleted" => 0
                ]
            )
            ->all();
        if (!empty($billingCharges)) {
            return $billingCharges[0];
        }
        return false;
    }


    public static function getBillingChargeById($billingChargeId)
    {
        return BillingCharge::find()->where(["id" => $billingChargeId])->one();
    }


    public static function generateApplicationSubmissionBillingCharge(
        $billingCharge,
        $applicationPeriodId,
        $userID
    ) {
        if (
            $billingCharge == true
            && $applicationPeriodId == true
            && $userID == true
        ) {
            $applicationPeriod =
                ApplicationPeriodModel::getApplicationPeriodByID(
                    $applicationPeriodId
                );

            $applicationSubmissionBillingType =
                BillingTypeModel::getActiveApplicationSubmissionBillingTypeByApplicationPeriod(
                    $applicationPeriod
                );

            $billingCharge->billing_type_id = $applicationSubmissionBillingType->id;
            $billingCharge->application_period_id = $applicationPeriodId;
            $billingCharge->modifier_id = $userID;
            return $billingCharge;
        }
        return null;
    }


    public static function processRequestToAddApplicationSubmissionBillingChargeToApplicationPeriod(
        $billingCharge,
        $applicationPeriodId,
        $userID
    ) {
        $billingCharge =
            self::generateApplicationSubmissionBillingCharge(
                $billingCharge,
                $applicationPeriodId,
                $userID
            );

        if ($billingCharge == false) {
            return new ErrorObject(
                "Error occurred generating application submission charge"
            );
        } elseif ($billingCharge == true && $billingCharge->save() == true) {
            return $billingCharge;
        } else {
            return new ErrorObject(
                "Error occurred saving application submission charge"
            );
        }
    }


    public static function generateApplicationAmendmentBillingCharge(
        $billingCharge,
        $applicationPeriodId,
        $userID
    ) {
        if (
            $billingCharge == true
            && $applicationPeriodId == true
            && $userID == true
        ) {
            $applicationPeriod =
                ApplicationPeriodModel::getApplicationPeriodByID(
                    $applicationPeriodId
                );

            $applicationSubmissionBillingType =
                BillingTypeModel::getActiveApplicationAmendmentBillingTypeByApplicationPeriod(
                    $applicationPeriod
                );

            $billingCharge->billing_type_id = $applicationSubmissionBillingType->id;
            $billingCharge->application_period_id = $applicationPeriodId;
            $billingCharge->modifier_id = $userID;
            return $billingCharge;
        }
        return null;
    }


    public static function getRelevantApplicationBillingChargeForApplicant(
        $personid,
        $billingType
    ) {
        $activeApplications =
            ApplicationModel::getActiveApplicationsByPersonID($personid);

        if ($activeApplications == true) {
            $application = $activeApplications[0];

            $offering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $application->academicofferingid
                );

            if (
                $offering == true
                && $billingType === "Application Submission"
            ) {
                return BillingChargeModel::getActiveApplicationSubmissionBillingChargeByApplicationPeriodId(
                    $offering->applicationperiodid
                );
            } elseif (
                $offering == true
                && $billingType === "Application Amendment"
            ) {
                return BillingChargeModel::getActiveApplicationAmendmentBillingChargeByApplicationPeriodId(
                    $offering->applicationperiodid
                );
            }

            return null;
        }
    }


    public static function getInactiveApplicationSubmissionBillingChargesByApplicationPeriodId(
        $applicationPeriodId
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where(
                [
                    "billing_charge.application_period_id" => $applicationPeriodId,
                    "billing_type.name" => "Application Submission",
                    "billing_charge.is_active" => 0,
                    "billing_charge.is_deleted" => 0
                ]
            )
            ->all();
    }


    public static function getFormattedAssociativeArrayOfPastApplicationSubmissionBilllingChargesForApplicationPeriod(
        $applicationPeriodId
    ) {
        $billingCharges =
            self::getInactiveApplicationSubmissionBillingChargesByApplicationPeriodId(
                $applicationPeriodId
            );

        $listing = array();
        foreach ($billingCharges as $billingCharge) {
            $listing[$billingCharge->id] = $billingCharge->cost;
        }
        return $listing;
    }


    public static function revertBillingCharge(
        $fromBillingChargeId,
        $toBillingChargeId
    ) {
        $fromBillingCharge =
            BillingChargeModel::getBillingChargeById($fromBillingChargeId);

        $toBillingCharge =
            BillingChargeModel::getBillingChargeById($toBillingChargeId);

        if ($fromBillingCharge == true && $toBillingCharge == true) {
            $fromBillingCharge->is_active = 0;
            $toBillingCharge->is_active = 1;

            if (
                $fromBillingCharge->save() == true
                && $toBillingCharge->save() == true
            ) {
                return true;
            }
        }
        return false;
    }


    public static function generateUpdatedApplicationSubmissionBillingCharge(
        $oldBillingCharge,
        $newBillingCharge,
        $userID
    ) {
        $oldBillingCharge->is_active = 0;

        $targetApplicationPeriod =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $oldBillingCharge->application_period_id
            );

        $applicationSubmissionBillingType =
            BillingTypeModel::getActiveApplicationSubmissionBillingTypeByApplicationPeriod(
                $targetApplicationPeriod
            );

        $newBillingCharge->billing_type_id = $applicationSubmissionBillingType->id;
        $newBillingCharge->application_period_id = $oldBillingCharge->application_period_id;
        $newBillingCharge->modifier_id = $userID;

        if ($oldBillingCharge->save() == true && $newBillingCharge->save() == true) {
            return true;
        }
        return false;
    }


    public static function getInactiveApplicationAmendmentBillingChargesByApplicationPeriodId(
        $applicationPeriodId
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where(
                [
                    "billing_charge.application_period_id" => $applicationPeriodId,
                    "billing_type.name" => "Application Amendment",
                    "billing_charge.is_active" => 0,
                    "billing_charge.is_deleted" => 0
                ]
            )
            ->all();
    }


    public static function getFormattedAssociativeArrayOfPastApplicationAmendmentBilllingChargesForApplicationPeriod(
        $applicationPeriodId
    ) {
        $billingCharges =
            self::getInactiveApplicationAmendmentBillingChargesByApplicationPeriodId(
                $applicationPeriodId
            );

        $listing = array();
        foreach ($billingCharges as $billingCharge) {
            $listing[$billingCharge->id] = $billingCharge->cost;
        }
        return $listing;
    }


    public static function generateUpdatedApplicationAmendmentBillingCharge(
        $oldBillingCharge,
        $newBillingCharge,
        $userID
    ) {
        $oldBillingCharge->is_active = 0;
        $applicationAmendmentBillingType =
            BillingTypeModel::getBillingTypeByName("Application Amendment");
        $newBillingCharge->billing_type_id = $applicationAmendmentBillingType->id;
        $newBillingCharge->application_period_id = $oldBillingCharge->application_period_id;
        $newBillingCharge->modifier_id = $userID;

        if ($oldBillingCharge->save() == true && $newBillingCharge->save() == true) {
            return true;
        }
        return false;
    }


    public static function prepareStudentFeeApplicationPeriodCatalog()
    {
        $data = array();
        $periods = ApplicationPeriodModel::getActiveApplicationPeriods();

        foreach ($periods as $period) {
            $data[] =
                self::generateApplicationPeriodStudentFeeSummary($period);
        }
        return $data;
    }

    /**
     * 
     * 
     * @param ApplicationPeriod $period
     * @return mixed
     */
    public static function generateApplicationPeriodStudentFeeSummary($period)
    {
        $record = array();
        $record["applicationPeriodId"] = $period->applicationperiodid;
        $record["periodName"] =  $period->name;

        $fees = self::getActiveStudentFeesForApplicationPeriod($period);
        if (empty($fees) == true) {
            $record["status"] =  "No fees entered";
        } else {
            $record["status"] =  count($fees) . " fees entered";
        }

        $record["academicOfferings"] =
            $period->getAcademicOfferings()->where(['isactive' => 1])->all();

        return $record;
    }


    public static function getActiveStudentFeesForApplicationPeriod($period)
    {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.application_period_id" => $period->applicationperiodid,
                "billing_scope.name" => "Student",
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0
            ])
            ->all();
    }


    public static function getActiveStudentFeesForAcademicOffering(
        $academicOffering
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.academic_offering_id" =>
                $academicOffering->academicofferingid,
                "billing_scope.name" => "Student",
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0
            ])
            ->all();
    }


    public static function prepareApplicationPeriodStudentFeeBillingChargesCatalog(
        $applicationPeriod
    ) {
        $data = array();

        $billingCharges =
            self::getActiveStudentFeesForApplicationPeriod($applicationPeriod);

        foreach ($billingCharges as $billingCharge) {
            $data[] =
                self::generateBillingChargeRecord($billingCharge);
        }
        return $data;
    }


    public static function generateBillingChargeRecord($billingCharge)
    {
        $record = array();
        $record["billingChargeId"] = $billingCharge->id;
        $record["applicationPeriodId"] = $billingCharge->application_period_id;
        $record["billingTypeId"] = $billingCharge->billing_type_id;

        $billingType =
            BillingTypeModel::getBillingTypeByID(
                $billingCharge->billing_type_id
            );
        $record["billingTypeName"] = $billingType->name;

        $record["programme"] =
            self::getBillingChargeProgrammeDescription($billingCharge);

        $record["cost"] = $billingCharge->cost;

        $record["pastBillingChargeInformation"] =
            self::getFormattedAssociativeArrayOfRelatedPastBilllingCharges(
                $billingCharge
            );

        $record["payable_on_enrollment"] = $billingCharge->payable_on_enrollment;
        return $record;
    }


    public static function getBillingChargeProgrammeDescription($billingCharge)
    {
        if ($billingCharge->academic_offering_id == false) {
            return "N/A";
        } else {
            $academicOffering =
                AcademicOfferingModel::getAcademicOfferingByID(
                    $billingCharge->academic_offering_id
                );

            $programmeCatalog =
                ProgrammeCatalogModel::getProgrammeCatalogByID(
                    $academicOffering->programmecatalogid
                );

            return ProgrammeCatalogModel::getFormattedProgrammeName($programmeCatalog);
        }
    }


    public static function getFormattedAssociativeArrayOfRelatedPastBilllingCharges(
        $billingCharge
    ) {
        $pastBillingCharges =
            self::getInactiveRelatedBillingCharges($billingCharge);

        $listing = array();
        foreach ($pastBillingCharges as $billingCharge) {
            $listing[$billingCharge->id] = $billingCharge->cost;
        }
        return $listing;
    }


    public static function getInactiveRelatedBillingCharges($billingCharge)
    {
        return BillingCharge::find()
            ->where(
                [
                    "application_period_id" => $billingCharge->application_period_id,
                    "billing_type_id" => $billingCharge->billing_type_id,
                    "academic_offering_id" => $billingCharge->academic_offering_id,
                    "is_active" => 0,
                    "is_deleted" => 0
                ]
            )
            ->all();
    }


    public static function hasValidBillingChargeForm($billingChargeForms)
    {
        foreach ($billingChargeForms as $billingChargeForm) {
            if ($billingChargeForm->isValid() == true) {
                return true;
            }
        }
        return false;
    }


    public static function generateBillingCharges(
        $applicationPeriodId,
        $billingChargeForms,
        $userId
    ) {
        if (self::hasValidBillingChargeForm($billingChargeForms) == false) {
            return new ErrorObject("At least one (1) fee must be added.");
        } else {
            foreach ($billingChargeForms as $key => $billingChargeForm) {
                if (
                    $billingChargeForm->isValid() == true
                    && $billingChargeForm->hasDuplicateRecord($applicationPeriodId) == false
                ) {
                    if ($billingChargeForm->generateBillingChargeModel(
                        $applicationPeriodId,
                        $userId
                    ) == false) {
                        $loc = $key + 1;
                        return new ErrorObject("Error ocurred saving record #{$loc}");
                    }
                }
            }
        }
    }


    public static function generateUpdatedBillingCharge(
        $oldBillingCharge,
        $newBillingCharge,
        $userID
    ) {
        $oldBillingCharge->is_active = 0;
        $newBillingCharge->payable_on_enrollment = $oldBillingCharge->payable_on_enrollment;
        $newBillingCharge->billing_type_id = $oldBillingCharge->billing_type_id;
        $newBillingCharge->application_period_id = $oldBillingCharge->application_period_id;
        $newBillingCharge->academic_offering_id = $oldBillingCharge->academic_offering_id;
        $newBillingCharge->modifier_id = $userID;

        if ($oldBillingCharge->save() == true && $newBillingCharge->save() == true) {
            return true;
        }
        return false;
    }


    public static function processRequestToAddApplicationAmendmentBillingChargeToApplicationPeriod(
        $billingCharge,
        $applicationPeriodId,
        $userID
    ) {
        $billingCharge =
            self::generateApplicationAmendmentBillingCharge(
                $billingCharge,
                $applicationPeriodId,
                $userID
            );

        if ($billingCharge == false) {
            return new ErrorObject(
                "Error occurred generating application submission charge"
            );
        } elseif ($billingCharge == true && $billingCharge->save() == true) {
            return $billingCharge;
        } else {
            return new ErrorObject(
                "Error occurred saving application submission charge"
            );
        }
    }


    public static function getStudentBillingChargesByApplicationPeriodId(
        $applicationPeriodId
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.application_period_id" => $applicationPeriodId,
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0,
                "billing_scope.name" => "Student"
            ])
            ->all();
    }


    public static function getStudentBillingChargesByAcademicOffering(
        $academicOffering
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.academicofferingid" =>
                $academicOffering->academicofferingid,
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0,
                "billing_scope.name" => "Student"
            ])
            ->all();
    }


    public static function formatBillingChargeForCatalog(
        $billingCharge
    ) {
        $record = array();
        $record["billingChargeId"] = $billingCharge->id;
        $record["billingTypeId"] = $billingCharge->billing_type_id;

        $record["billingType"] =
            BillingTypeModel::getBillingTypeByID($billingCharge->billing_type_id)
            ->name;

        if ($billingCharge->academic_offering_id == true) {
            $record["class"] = "Programme Specific";
        } else {
            $record["class"] = "Cohort";
        }

        $record["applicationPeriodId"] = $billingCharge->application_period_id;
        $record["academicOfferingId"] = $billingCharge->academic_offering_id;
        $record["cost"] = $billingCharge->cost;

        $record["pastCosts"] =
            self::getFormattedAssociativeArrayOfPastBilllingCharges($billingCharge);

        $record["payable_on_enrollment"] = $billingCharge->payable_on_enrollment;
        return $record;
    }


    public static function prepareApplicationPeriodOfferingFeeCatalog(
        $applicationPeriodId
    ) {
        $data = array();

        $billingCharges =
            self::getStudentBillingChargesByApplicationPeriodId(
                $applicationPeriodId
            );

        foreach ($billingCharges as $billingCharge) {
            $data[] =
                self::formatBillingChargeForCatalog(
                    $billingCharge
                );
        }

        return $data;
    }

    public static function prepareAcademicOfferingFeeCatalog($academicOffering)
    {
        $data = array();

        $billingCharges =
            self::getStudentBillingChargesByApplicationPeriodId(
                $academicOffering->applicationperiodid
            );

        if (!empty($billingCharges)) {
            foreach ($billingCharges as $key => $billingCharge) {
                if (
                    $billingCharge->academic_offering_id != null
                    && $billingCharge->academic_offering_id != $academicOffering->academicofferingid
                ) {
                    unset($billingCharges[$key]);
                }
            }
        }

        foreach ($billingCharges as $billingCharge) {
            $data[] = self::formatBillingChargeForCatalog($billingCharge);
        }

        return $data;
    }


    public static function getInactiveBillingCharges(
        $applicationPeriodId,
        $billingTypeId
    ) {
        return BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where(
                [
                    "billing_charge.application_period_id" => $applicationPeriodId,
                    "billing_type.id" => $billingTypeId,
                    "billing_charge.is_active" => 0,
                    "billing_charge.is_deleted" => 0
                ]
            )
            ->all();
    }


    public static function getFormattedAssociativeArrayOfPastBilllingCharges(
        $billingCharge
    ) {
        $billingCharges =
            self::getInactiveBillingCharges(
                $billingCharge->application_period_id,
                $billingCharge->billing_type_id
            );

        $listing = array();
        foreach ($billingCharges as $billingCharge) {
            $listing[$billingCharge->id] = $billingCharge->cost;
        }
        return $listing;
    }


    public static function getBillingChargeFeeName($billingCharge)
    {
        $billingType =
            BillingTypeModel::getBillingTypeByID($billingCharge->billing_type_id);

        if ($billingType == true) {
            return $billingType->name;
        } else {
            return null;
        }
    }


    public static function getOutstandingEnrollmentChargesByApplication(
        $application
    ) {
        $outstandingCharges = array();

        $cohortCharges =
            self::getCohortBillingChargesPayableOnEnrollment($application);

        $programmeCharges =
            self::getProgrammeBillingChargesPayableOnEnrollment($application);

        $billingChargesCatalog = array_merge($cohortCharges, $programmeCharges);

        foreach ($billingChargesCatalog as $billingCharge) {
            if (
                self::customerBillingChargeIsOutstanding(
                    $billingCharge,
                    $application->personid
                )
                == true
            ) {
                $outstandingCharges[] = $billingCharge;
            }
        }
        return $outstandingCharges;
    }


    public static function customerBillingChargeIsOutstanding(
        $billingCharge,
        $customerId
    ) {
        $customerBillings =
            BillingModel::getCustomerFeePayments(
                $billingCharge->id,
                $customerId
            );
        if (
            $customerBillings == false
            || ($customerBillings == true
                && BillingModel::calculateTotalPaidOnBillingCharge(
                    $billingCharge->id,
                    $customerId
                ) < $billingCharge->cost)
        ) {
            return true;
        }
        return false;
    }


    public static function getAllOutstandingBillingCharges(
        $studentRegistration
    ) {
        $outstandingCharges = array();

        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $studentRegistration->academicofferingid
            );

        $billingChargeCatalog =
            self::getBillingChargesForApplication($academicOffering);

        foreach ($billingChargeCatalog as $billingCharge) {
            if (
                self::customerBillingChargeIsOutstanding(
                    $billingCharge,
                    $studentRegistration->personid
                )
                == true
            ) {
                $outstandingCharges[] = $billingCharge;
            }
        }
        return $outstandingCharges;
    }


    public static function getFirstAndSecondYearBillingChargesForApplication($application)
    {
        $cohortCharges =
            self::getFirstAndSecondYearCohortBillingCharges($application);

        $programmeCharges =
            self::getFirstAndSecondYearProgrammeBillingCharges(
                $application
            );

        return array_merge($cohortCharges, $programmeCharges);
    }


    public static function getFirstAndSecondYearCohortBillingCharges(
        $application
    ) {
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $application->academicofferingid
            );

        $billingCharges = BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.application_period_id" => $academicOffering->applicationperiodid,
                "billing_charge.academic_offering_id" => NULL,
                "billing_scope.name" => "Student",
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0
            ])
            ->all();

        if (!empty($billingCharges)) {
            return $billingCharges;
        } else {
            return array();
        }
    }


    public static function getCohortBillingChargesPayableOnEnrollment(
        $application
    ) {
        $academicOffering =
            AcademicOfferingModel::getAcademicOfferingByID(
                $application->academicofferingid
            );

        if ($academicOffering == true) {
            return BillingCharge::find()
                ->innerJoin(
                    'billing_type',
                    '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
                )
                ->innerJoin(
                    'billing_category',
                    '`billing_type`.`billing_category_id` = `billing_category`.`id`'
                )
                ->innerJoin(
                    'billing_scope',
                    '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
                )
                ->where([
                    "billing_charge.application_period_id" => $academicOffering->applicationperiodid,
                    "billing_charge.payable_on_enrollment" => 1,
                    "billing_charge.academic_offering_id" => NULL,
                    "billing_scope.name" => "Student",
                    "billing_charge.is_active" => 1,
                    "billing_charge.is_deleted" => 0
                ])
                ->all();
        } else {
            return array();
        }
    }


    public static function getFirstAndSecondYearProgrammeBillingCharges(
        $application
    ) {
        $screenedBillingCharges = array();

        $billingCharges =
            BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.academic_offering_id" => $application->academicofferingid,
                "billing_scope.name" => "Student",
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0
            ])
            ->all();

        if (!empty($billingCharges)) {
            $screenedBillingCharges =
                BillingChargeModel::screenForCapeLaboratoryFeeBillings(
                    $billingCharges,
                    $application
                );
        }

        return $screenedBillingCharges;
    }


    public static function screenForCapeLaboratoryFeeBillings(
        $billingCharges,
        $application
    ) {
        if (ApplicationModel::isCape($application) == false) {
            return $billingCharges;
        } else {
            $capeSubjects =
                ApplicationModel::getSubjectsForCapeApplication($application);

            $capeSubjectNames =
                CapeSubjectModel::getCapeSubjectNames($capeSubjects);

            foreach ($billingCharges as $key => $billingCharge) {
                $billingType = self::getBillingChargeFeeName($billingCharge);
                if (strpos($billingType, "Laboratory Fee (CAPE") === false) {
                    continue;
                } else {
                    $matchFound = false;
                    foreach ($capeSubjectNames as $name) {
                        if (strpos($billingType, $name) !== false) {
                            $matchFound = true;
                            break;
                        }
                    }

                    if ($matchFound == false) {
                        unset($billingCharges[$key]);
                    }
                }
            }
            return $billingCharges;
        }
    }


    public static function getProgrammeBillingChargesPayableOnEnrollment(
        $application
    ) {
        $screenedBillingCharges = array();

        $billingCharges =
            BillingCharge::find()
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->innerJoin(
                'billing_category',
                '`billing_type`.`billing_category_id` = `billing_category`.`id`'
            )
            ->innerJoin(
                'billing_scope',
                '`billing_category`.`billing_scope_id` = `billing_scope`.`id`'
            )
            ->where([
                "billing_charge.academic_offering_id" => $application->academicofferingid,
                "billing_charge.payable_on_enrollment" => 1,
                "billing_scope.name" => "Student",
                "billing_charge.is_active" => 1,
                "billing_charge.is_deleted" => 0
            ])
            ->all();

        if (!empty($billingCharges)) {
            $screenedBillingCharges =
                BillingChargeModel::screenForCapeLaboratoryFeeBillings(
                    $billingCharges,
                    $application
                );
        }

        return $screenedBillingCharges;
    }
}
