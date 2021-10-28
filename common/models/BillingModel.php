<?php

namespace common\models;

class BillingModel
{
    public static function getApplicantApplicationSubmissionBilling($applicant)
    {
        if ($applicant == null) {
            return null;
        }

        $applicationPeriodID =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        return Billing::find()
            ->innerJoin(
                'billing_charge',
                '`billing`.`billing_charge_id` = `billing_charge`.`id`'
            )
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where([
                "billing.customer_id" => $applicant->personid,
                "billing.application_period_id" => $applicationPeriodID,
                "billing.is_active" => 1,
                "billing.is_deleted" => 0,
                "billing_type.name" => "Application Submission"
            ])
            ->one();
    }


    public static function getApplicantApplicationAmendmentBilling($applicant)
    {
        $applicationPeriodID =
            ApplicantModel::getApplicantApplicationPeriodID($applicant);

        return Billing::find()
            ->innerJoin(
                'billing_charge',
                '`billing`.`billing_charge_id` = `billing_charge`.`id`'
            )
            ->innerJoin(
                'billing_type',
                '`billing_charge`.`billing_type_id` = `billing_type`.`id`'
            )
            ->where([
                "billing.customer_id" => $applicant->personid,
                "billing.application_period_id" => $applicationPeriodID,
                "billing.is_active" => 1,
                "billing.is_deleted" => 0,
                "billing_type.name" => "Application Ammendment"
            ])
            ->one();
    }


    public static function getBillingById($id)
    {
        return Billing::find()
            ->where([
                "id" => $id, "is_active" => 1, "is_deleted" => 0
            ])->one();
    }


    public static function softDeleteBilling($billing, $userId)
    {
        $billing->is_active = 0;
        $billing->is_deleted = 1;
        $billing->modified_by = $userId;
        return $billing->save();
    }


    public static function getReceipt($billing)
    {
        return Receipt::find()->where(["id" => $billing->receipt_id])->one();
    }


    public static function deleteBilling($billing, $userId)
    {
        return self::softDeleteBilling($billing, $userId);
    }


    public static function getCustomerFeePayments($billingChargeId, $personid)
    {
        return Billing::find()
            ->where([
                "customer_id" => $personid,
                "billing_charge_id" => $billingChargeId,
                "is_active" => 1,
                "is_deleted" => 0
            ])
            ->all();
    }


    public static function calculateTotalPaidOnBillingCharge(
        $billingChargeId,
        $personid
    ) {
        $billings =
            BillingModel::getCustomerFeePayments($billingChargeId, $personid);

        if ($billings == true) {
            $total = 0;
            foreach ($billings as $billing) {
                $total += $billing->amount_paid;
            }
            return $total;
        }
        return 0;
    }


    public static function calculateOutstandingAmountOnBillingCharge(
        $billingChargeId,
        $personid
    ) {
        $billingCharge =
            BillingChargeModel::getBillingChargeById($billingChargeId);

        $billings =
            BillingModel::getCustomerFeePayments($billingChargeId, $personid);

        if ($billings == false) {
            return $billingCharge->cost;
        } else {
            $total = 0;
            foreach ($billings as $billing) {
                $total += $billing->amount_paid;
            }
            return $billingCharge->cost - $total;
        }
    }


    public static function generateOutstandingBilling(
        $billingCharge,
        $customerId
    ) {
        $billing = new Billing();
        $billing->billing_charge_id = $billingCharge->id;
        $billing->customer_id = $customerId;
        $billing->application_period_id = $billingCharge->application_period_id;

        $billing->cost =
            self::calculateOutstandingAmountOnBillingCharge(
                $billingCharge->id,
                $customerId
            );

        return $billing;
    }


    public static function getBillingsByDate($searchForm)
    {
        $start = $searchForm->startDate;
        $end = $searchForm->endDate;
        if ($start == false && $end == false) {
            return Billing::find()
                ->where([
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->all();
        } elseif ($start == true && $end == false) {
            return Billing::find()
                ->innerJoin(
                    'receipt',
                    '`billing`.`receipt_id` = `receipt`.`id`'
                )
                ->where([
                    "billing.is_active" => 1,
                    "billing.is_deleted" => 0
                ])
                ->andWhere(['>=', 'receipt.date_paid', $start])
                ->all();
        } elseif ($start == false && $end == true) {
            return Billing::find()
                ->innerJoin(
                    'receipt',
                    '`billing`.`receipt_id` = `receipt`.`id`'
                )
                ->where([
                    "billing.is_active" => 1,
                    "billing.is_deleted" => 0
                ])
                ->andWhere(['<=', 'receipt.date_paid', $end])
                ->all();
        } elseif ($start == true && $end == true) {
            return Billing::find()
                ->innerJoin(
                    'receipt',
                    '`billing`.`receipt_id` = `receipt`.`id`'
                )
                ->where([
                    "billing.is_active" => 1,
                    "billing.is_deleted" => 0
                ])
                ->andWhere(['>=', 'receipt.date_paid', $start])
                ->andWhere(['<=', 'receipt.date_paid', $end])
                ->all();
        }
    }


    public static function prepareFormattedBillingListing($billings)
    {
        $dataSet = array();
        foreach ($billings as $billing) {
            $record = self::convertBillingInfoIntoAssociativeArray($billing);
            $dataSet[] = $record;
        }
        return $dataSet;
    }


    public static function convertBillingInfoIntoAssociativeArray($billing)
    {
        $record = array();

        $receipt = ReceiptModel::getReceiptById($billing->receipt_id);

        $paymentMethod =
            PaymentMethodModel::getPaymentMethodByID(
                $receipt->payment_method_id
            );

        $billingCharge =
            BillingChargeModel::getBillingChargeById(
                $billing->billing_charge_id
            );

        $billingType =
            BillingTypeModel::getBillingTypeById(
                $billingCharge->billing_type_id
            );

        $period =
            ApplicationPeriodModel::getApplicationPeriodByID(
                $billingCharge->application_period_id
            );

        $customer = UserModel::getUserById($receipt->customer_id);
        $accountClassification = UserModel::getUserClassification($customer);
        if ($accountClassification == "Student") {
            $record["customerStatus"] = "student-profile";
        } elseif ($accountClassification == "Successful Applicant") {
            $record["customerStatus"] = "successful-applicant-profile";
        } elseif ($accountClassification == "Completed Applicant") {
            $record["customerStatus"] = "completed-applicant-profile";
        }

        $record["receiptId"] = $receipt->id;
        $record["receiptNumber"] = $receipt->receipt_number;
        $record["date"] = $receipt->date_paid;
        $record["customerUsername"] = $receipt->username;
        $record["customerFullName"] = $receipt->full_name;

        $record["billingType"] = $billingType->name;
        $record["applicationPeriod"] = $period->name;
        $record["paymentMethod"] = $paymentMethod->name;
        $record["amountDue"] = $billing->cost;
        $record["amountPaid"] = $billing->amount_paid;
        return $record;
    }
}
