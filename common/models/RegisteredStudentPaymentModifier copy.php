<?php

namespace common\models;

class RegisteredStudentPaymentModifier
{
    private $oldReceipt;
    private $oldBillings;
    private $newReceiptForm;
    private $updateBillingForms;
    private $staffId;
    private $staffName;
    private $newReceipt;
    private $newBillings;

    public function _construct(
        $oldReceipt,
        $oldBillings,
        $newReceiptForm,
        $updateBillingForms,
        $staffId,
        $staffName
    ) {
        $this->oldReceipt = $oldReceipt;
        $this->oldBillings = $oldBillings;
        $this->newReceiptForm = $newReceiptForm;
        $this->updateBillingForms = $updateBillingForms;
        $this->staffId = $staffId;
        $this->staffName = $staffName;
        $this->newReceipt = new Receipt();
        $this->newBillings = array();
    }


    private function formatTimeStamp($timestamp)
    {
        return date_format(new \DateTime($timestamp), "F j, Y");
    }


    private function generateReceiptCancellationMessage($newReceiptNumber)
    {
        $dateVoided = $this->formatTimeStamp($this->oldReceipt->modified_at);

        return "Receipt# {$this->oldReceipt->receipt_number}"
            . " was voided by {$this->staffName} on {$dateVoided} due to an"
            . " error in its billing information. Please refer to"
            . " Receipt# {$newReceiptNumber} for correct payment information.";
    }


    private function voidOldReceipt($newReceiptNumber)
    {
        $this->oldReceipt->is_active = 0;
        $this->oldReceipt->is_deleted = 0;
        $this->oldReceipt->modified_by = $this->staffId;
        $this->oldReceipt->modified_at = date("Y-m-d H:i:s");

        $this->oldReceipt->notes =
            $this->generateReceiptCancellationMessage($newReceiptNumber);

        if ($this->oldReceipt->validate() == false) {
            return false;
        } else {
            return $this->oldReceipt;
        }
    }


    private function voidBilling($billing)
    {
        $billing->is_active = 0;
        $billing->is_deleted = 0;
        $billing->modified_by = $this->staffId;
        $billing->modified_at = date("Y-m-d H:i:s");
        return $billing;
    }


    private function voidOldBillings()
    {
        foreach ($this->oldBillings as $billing) {
            $billing = $this->voidBilling($billing);
            if ($billing->validate() == false) {
                return false;
            }
        }
        return $this->oldBillings;
    }


    private function padToFourCharacterString($numAsString)
    {
        $length = strlen($numAsString);
        if ($length == 1) {
            return "000{$numAsString}";
        } elseif ($length == 2) {
            return "00{$numAsString}";
        } elseif ($length == 3) {
            return "0{$numAsString}";
        } elseif ($length == 4) {
            return $numAsString;
        }
    }


    private function getLastReceiptId()
    {
        $receipts = Receipt::find()->orderBy("id DESC")->all();
        if (!empty($receipts)) {
            $id = $receipts[0]->id;
            $idAsFourCharacterString = strval($id % 10000);
            return $this->padToFourCharacterString($idAsFourCharacterString);
        } else {
            return "0000";
        }
    }


    private function generateReceiptNumber()
    {
        $unformattedDate = date('Y-m-d');
        $yearSegment = substr($unformattedDate, 0, 4);
        $monthSegment = substr($unformattedDate, 5, 2);
        $daySegment = substr($unformattedDate, 8, 2);
        $idSegment = $this->getLastReceiptId();
        return "{$yearSegment}{$monthSegment}{$daySegment}{$idSegment}";
    }


    private function generateNewReceipt()
    {
        $this->newReceipt->payment_method_id =
            // $this->newReceiptForm->paymentMethodId;

            $this->newReceipt->customer_id = $this->oldReceipt->customer_id;

        $this->newReceipt->student_registration_id =
            $this->oldReceipt->studentregistrationid;

        $this->newReceipt->created_by = $this->staffId;
        $this->newReceipt->username = $this->newReceiptForm->username;
        $this->newReceipt->full_name = $this->newReceiptForm->fullName;
        $this->newReceipt->receipt_number = $this->generateReceiptNumber();
        $this->newReceipt->email = $this->oldReceipt->email;
        $this->newReceipt->date_paid = $this->newReceiptForm->datePaid;

        $this->newReceipt->cheque_number =
            ($this->newReceiptForm->chequeNumber == true) ?
            $this->newReceiptForm->chequeNumber : null;

        $this->newReceipt->timestamp = date("Y-m-d H:i:s");
        if ($this->newReceipt->validate() == false) {
            return false;
        } else {
            return $this->newReceipt;
        }
    }

    // private function generateNewReceipt()
    // {
    //     $receipt = new Receipt();
    //     $receipt->payment_method_id = $this->newReceiptForm->paymentMethodId;
    //     $receipt->customer_id = $this->oldReceipt->customer_id;
    //     $receipt->student_registration_id = $this->oldReceipt->studentregistrationid;
    //     $receipt->created_by = $this->staffId;
    //     $receipt->username = $this->newReceiptForm->username;
    //     $receipt->full_name = $this->newReceiptForm->fullName;
    //     $receipt->receipt_number = $this->generateReceiptNumber();
    //     $receipt->email = $this->oldReceipt->email;
    //     $receipt->date_paid = $this->newReceiptForm->datePaid;

    //     $receipt->cheque_number =
    //         ($this->newReceiptForm->chequeNumber == true) ?
    //         $this->newReceiptForm->chequeNumber : null;

    //     $receipt->timestamp = date("Y-m-d H:i:s");
    //     if ($receipt->validate() == false) {
    //         return false;
    //     } else {
    //         return $receipt;
    //     }
    // }


    private function generateNewBilling(
        $billingForm,
        $receipt,
        $billingCharge
    ) {
        $billing = new Billing();
        $billing->receipt_id = $receipt->id;
        $billing->billing_charge_id = $billingForm->billingChargeId;
        $billing->customer_id = $receipt->customer_id;
        $billing->application_period_id = $billingCharge->application_period_id;
        $billing->student_registration_id = $receipt->student_registration_id;
        $billing->academic_offering_id = $billingCharge->academic_offering_id;
        $billing->created_by = $receipt->created_by;
        $billing->created_at = $receipt->timestamp;
        $billing->cost = $billingForm->balance;
        $billing->amount_paid = $billingForm->amountPaid;
        if ($billing->validate() == true) {
            return $billing;
        } else {
            return false;
        }
    }


    private function generateNewBillings($receipt)
    {
        foreach ($this->updateBillingForms as $billingForm) {
            if ($billingForm->isActive == false) {
                continue;
            }

            $billingCharge =
                BillingChargeModel::getBillingChargeById(
                    $billingForm->billingChargeId
                );

            $billing =
                $this->generateNewBilling($billingForm, $receipt, $billingCharge);

            if ($billing == true) {
                $this->newBillings[] = $billing;
            }
        }
        return $this->newBillings;
    }


    private function saveNewReceipt()
    {
        return $this->newReceipt->save();
    }


    private function saveOldReceipt()
    {
        return $this->oldReceipt->save();
    }


    private function saveNewBillings()
    {
        foreach ($this->newBillings as $newBilling) {
            if ($newBilling->save() == false) {
                return false;
            }
        }
        return true;
    }


    private function saveOldBillings()
    {
        foreach ($this->oldBillings as $oldBilling) {
            if ($oldBilling->save() == false) {
                return false;
            }
        }
        return true;
    }


    private function processStudentDebits()
    {
        $oldReceiptTotal = ReceiptModel::calculateReceiptTotal($this->oldReceipt);
        $newReceiptTotal = ReceiptModel::calculateReceiptTotal($this->newReceipt);
        $credit = $newReceiptTotal - $oldReceiptTotal;

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $this->oldReceipt->student_registration_id
            );

        if ($credit > 0) {
            $studentRegistration->credit_amount = $credit;
            return $studentRegistration->save();
        }
        return true;
    }


    public function execute()
    {
        $newReceipt = $this->generateNewReceipt();
        $this->generateNewBillings($newReceipt);
        $this->voidOldReceipt($this->newReceipt->receipt_number);
        $this->voidOldBillings();

        if (
            $this->saveNewReceipt()
            && $this->saveNewBillings()
            && $this->saveOldReceipt()
            && $this->saveOldBillings()
            && $this->processStudentDebits()
        ) {
            return $this->newReceipt;
        } else {
            new ErrorObject("Error ocurred modifying payment");
        }
    }
}
