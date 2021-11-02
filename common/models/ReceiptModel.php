<?php

namespace common\models;

use Yii;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;

class ReceiptModel
{
    public static function getReceiptById($id)
    {
        return Receipt::find()
            ->where([
                "id" => $id, "is_active" => 1, "is_deleted" => 0
            ])
            ->one();
    }


    public static function getReceiptsByCustomerId($id)
    {
        return Receipt::find()
            ->where([
                "customer_id" => $id,
                "is_active" => 1,
                "is_deleted" => 0
            ])
            ->all();
    }


    public static function getBillings($receipt)
    {
        return Billing::find()
            ->where([
                "receipt_id" => $receipt->id,
                "is_active" => 1,
                "is_deleted" => 0
            ])
            ->all();
    }


    public static function formatReceiptDetailsIntoAssociativeArray(
        $receipt,
        $from
    ) {
        $data = array();
        if ($receipt != null) {
            $data["id"] = $receipt->id;
            $data["receiptNumber"] = $receipt->receipt_number;

            $total = 0;
            $billings = $receipt->getBillings()->all();
            foreach ($billings as $billing) {
                $total += $billing->amount_paid;
            }
            $data["total"] = $total;
            $data["datePaid"] = $receipt->date_paid;

            $data["applicationPeriod"] =
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $receipt->application_period_id
                );

            $user = UserModel::getUserById($receipt->customer_id);
            $data["username"] = $user->username;

            $data["from"] = $from;
        }
        return $data;
    }


    public static function prepareFormattedReceiptListing(
        $receipts,
        $from
    ) {
        $data = array();
        if ($receipts == true) {
            foreach ($receipts as $receipt) {
                $data[] =
                    self::formatReceiptDetailsIntoAssociativeArray(
                        $receipt,
                        $from
                    );
            }
        }

        return $data;
    }


    public static function formatSuccessfulApplicantReceiptDetailsIntoAssociativeArray($receipt)
    {
        $data = array();
        if ($receipt != null) {
            $data["id"] = $receipt->id;
            $data["receiptNumber"] = $receipt->receipt_number;

            $total = 0;
            $billingDetails = "";
            $billings = $receipt->getBillings()->all();
            foreach ($billings as $billing) {
                $billingCharge =
                    BillingChargeModel::getBillingChargeById($billing->billing_charge_id);

                $billingTypeName =
                    BillingChargeModel::getBillingChargeFeeName($billingCharge);

                $billingDetails .= "{$billingTypeName} \n";
                $total += $billing->amount_paid;
            }
            $data["billingDetails"] = $billingDetails;
            $data["total"] = $total;
            $data["datePaid"] = $receipt->date_paid;

            $data["applicationPeriod"] =
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $billings[0]->application_period_id
                );

            $user = UserModel::getUserById($receipt->customer_id);
            $data["username"] = $user->username;
        }
        return $data;
    }


    public static function prepareSuccessfulApplicantFormattedReceiptListing(
        $receipts
    ) {
        $data = array();
        if ($receipts == true) {
            foreach ($receipts as $receipt) {
                $data[] =
                    self::formatSuccessfulApplicantReceiptDetailsIntoAssociativeArray($receipt);
            }
        }

        return $data;
    }


    public static function calculateReceiptTotal($receipt)
    {
        $total = 0;
        $billings = $receipt->getBillings()->all();
        foreach ($billings as $billing) {
            $total += $billing->amount_paid;
        }
        return $total;
    }


    public static function deleteReceipt($receipt, $billings, $userId)
    {
        $receipt->is_active = 0;
        $receipt->is_deleted = 1;
        $receipt->modified_by = $userId;
        if ($receipt->save() == true) {
            foreach ($billings as $billing) {
                $billing->is_active = 0;
                $billing->is_deleted = 1;
                $billing->modified_by = $userId;
                if ($billing->save() == false) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }


    public static function prepareReceiptContent(
        $controller,
        $receipt,
        $billings,
        $applicantName,
        $applicantId
    ) {
        $total = number_format(self::calculateReceiptTotal($receipt), 2);

        return $controller->renderPartial(
            "receipt-template",
            [
                "receipt" => $receipt,
                "billings" => $billings,
                "total" => $total,
                "applicantName" => $applicantName,
                "applicantId" => $applicantId
            ]
        );
    }


    public static function generateReceiptForDownload(
        $controller,
        $receipt,
        $billings,
        $applicantName,
        $applicantId
    ) {
        $pdf =
            new Pdf([
                "filename" => "{$receipt->receipt_number}-Receipt.pdf",
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' =>
                self::prepareReceiptContent(
                    $controller,
                    $receipt,
                    $billings,
                    $applicantName,
                    $applicantId
                ),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' =>  "Receipt {$receipt->receipt_number}"],
                'methods' => [
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
        return $pdf->render();
    }


    public static function determineFilePath($receipt)
    {
        $basePath =
            Yii::getAlias("@frontend") . "/subcomponents/bursary/files/receipts";

        $filename = "{$receipt->receipt_number}.pdf";
        $filePath = "{$basePath}/{$filename}";

        if (file_exists($filePath) == true) {
            return $filePath;
        } else {
            return false;
        }
    }


    public static function createFilePath($receipt)
    {
        $basePath =
            Yii::getAlias("@frontend") . "/subcomponents/bursary/files/receipts";

        $filename = "{$receipt->receipt_number}.pdf";
        return "{$basePath}/{$filename}";
    }


    public static function generateReceiptFileToDirectory(
        $controller,
        $receipt,
        $billings,
        $applicantName,
        $applicantId
    ) {
        $pdf = new Pdf(
            [
                'filename' => self::createFilePath($receipt),
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'content' => self::prepareReceiptContent(
                    $controller,
                    $receipt,
                    $billings,
                    $applicantName,
                    $applicantId
                ),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' =>  "Receipt {$receipt->receipt_number}"],
                'methods' => [
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]
        );
        return $pdf->render();
    }


    public static function generateEmailForReceipt($receipt, $billings)
    {
        $emailTemplate = "bursary/receipt.php";
        $senderAddress = Yii::$app->params['bursaryEmail'];
        $destinationAddress = $receipt->email;
        $date_paid  = date_format(new \DateTime($receipt->date_paid), "F j, Y");

        $emailSubject =
            "Payment Receipt - {$receipt->receipt_number} - {$date_paid}";

        $filePath = self::determineFilePath($receipt);
        $total = self::calculateReceiptTotal($receipt);

        $customer = UserModel::getUserById($receipt->customer_id);
        $userFullName = UserModel::getUserFullname($customer);

        return Yii::$app->mailer
            ->compose(
                $emailTemplate,
                [
                    "userFullName" => $userFullName,
                    'receipt' => $receipt,
                    'billings' => $billings,
                    'total' => $total
                ]
            )
            ->setFrom($senderAddress)
            ->setTo($destinationAddress)
            ->setSubject($emailSubject)
            ->attach($filePath)
            ->send();
    }


    public static function publishReceipt(
        $controller,
        $receipt,
        $billings,
        $applicantName,
        $applicantId
    ) {
        self::generateReceiptFileToDirectory(
            $controller,
            $receipt,
            $billings,
            $applicantName,
            $applicantId
        );

        return self::generateEmailForReceipt($receipt, $billings);
    }


    public static function getReceiptsByDate($searchForm)
    {
        $start = $searchForm->startDate;
        $end = $searchForm->endDate;
        if ($start == false && $end == false) {
            return Receipt::find()
                ->where([
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->all();
        } elseif ($start == true && $end == false) {
            return Receipt::find()
                ->where([
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->andWhere(['>=', 'date_paid', $start])
                ->all();
        } elseif ($start == false && $end == true) {
            return Receipt::find()
                ->where([
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->andWhere(['<=', 'date_paid', $end])
                ->all();
        } elseif ($start == true && $end == true) {
            return Receipt::find()
                ->where([
                    "is_active" => 1,
                    "is_deleted" => 0
                ])
                ->andWhere(['>=', 'date_paid', $start])
                ->andWhere(['<=', 'date_paid', $end])
                ->all();
        }
    }


    public static function prepareFormattedReceiptReport($receipts)
    {
        $dataSet = array();
        foreach ($receipts as $receipt) {
            $record = self::convertReceiptReportInfoIntoAssociativeArray(
                $receipt
            );
            $dataSet[] = $record;
        }
        return $dataSet;
    }


    public static function convertReceiptReportInfoIntoAssociativeArray($receipt)
    {
        $record = array();

        $paymentMethod =
            PaymentMethodModel::getPaymentMethodByID(
                $receipt->payment_method_id
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
        $record["timestamp"] = $receipt->timestamp;


        $record["customerUsername"] = $receipt->username;
        $record["customerFullName"] = $receipt->full_name;

        $applicant =
            ApplicantModel::getApplicantByPersonid($receipt->customer_id);
        $record["customerFirstname"] = $applicant->firstname;
        $record["customerLastname"] = $applicant->lastname;

        $record["paymentMethod"] = $paymentMethod->name;

        $record["amountPaid"] =
            number_format(self::calculateReceiptTotal($receipt), 2);

        $paymentProcessor = UserModel::findUserByID($receipt->created_by);
        $record["paymentProcessor"] =
            Usermodel::getUserFullname($paymentProcessor);

        return $record;
    }


    public static function prepareCompletedApplicantFormattedReceiptListing(
        $receipts
    ) {
        $data = array();
        if ($receipts == true) {
            foreach ($receipts as $receipt) {
                $data[] =
                    self::formatCompletedApplicantReceiptDetailsIntoAssociativeArray(
                        $receipt
                    );
            }
        }

        return $data;
    }


    public static function formatCompletedApplicantReceiptDetailsIntoAssociativeArray(
        $receipt
    ) {
        $data = array();
        if ($receipt != null) {
            $data["id"] = $receipt->id;
            $data["receiptNumber"] = $receipt->receipt_number;

            $total = 0;
            $billings = ReceiptModel::getBillings($receipt);
            $billingDetails = "";
            foreach ($billings as $billing) {
                $billingCharge =
                    BillingChargeModel::getBillingChargeById($billing->billing_charge_id);

                $billingTypeName =
                    BillingChargeModel::getBillingChargeFeeName($billingCharge);

                $billingDetails .= "{$billingTypeName} \n";
                $total += $billing->amount_paid;
            }
            $data["billingDetails"] = $billingDetails;
            $data["total"] = $total;
            $data["datePaid"] = $receipt->date_paid;

            $data["applicationPeriod"] =
                ApplicationPeriodModel::getApplicationPeriodNameByID(
                    $billings[0]->application_period_id
                );

            $user = UserModel::getUserById($receipt->customer_id);
            $data["username"] = $user->username;
        }
        return $data;
    }
}
