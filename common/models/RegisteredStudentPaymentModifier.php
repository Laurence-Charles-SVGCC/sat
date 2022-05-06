<?php

namespace common\models;

class RegisteredStudentPaymentModifier
{
    private $oldReceipt;
    private $oldBillings;
    public $newReceiptForm;
    private $updateBillingForms;
    private $staffProfile;

    function __construct(
        $oldReceipt,
        $oldBillings,
        $newReceiptForm,
        $updateBillingForms,
        $staffProfile
    ) {
        $this->oldReceipt = $oldReceipt;
        $this->oldBillings = $oldBillings;
        $this->newReceiptForm = $newReceiptForm;
        $this->updateBillingForms = $updateBillingForms;
        $this->staffProfile = $staffProfile;
    }


    private function formatTimeStamp($timestamp)
    {
        return date_format(new \DateTime($timestamp), "F j, Y");
    }


    private function generateReceiptCancellationMessage($newReceiptNumber)
    {
        $dateVoided = $this->formatTimeStamp($this->oldReceipt->modified_at);

        return "Receipt# {$this->oldReceipt->receipt_number}"
            . " was voided by {$this->staffProfile->fullname} on {$dateVoided} due to an"
            . " error in its billing information. Please refer to"
            . " Receipt# {$newReceiptNumber} for correct payment information.";
    }


    private function voidOldReceipt($newReceiptNumber)
    {
        $this->oldReceipt->is_active = 0;
        $this->oldReceipt->is_deleted = 0;
        $this->oldReceipt->modified_by = $this->staffProfile->userId;
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
        $billing->modified_by = $this->staffProfile->userId;
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
        $newReceipt = new Receipt();
        $newReceipt->payment_method_id = $this->newReceiptForm->paymentMethodId;
        $newReceipt->customer_id = $this->oldReceipt->customer_id;
        $newReceipt->student_registration_id = $this->oldReceipt->student_registration_id;
        $newReceipt->created_by = $this->staffProfile->userId;
        $newReceipt->username = $this->newReceiptForm->username;
        $newReceipt->full_name = $this->newReceiptForm->fullName;
        $newReceipt->receipt_number = $this->generateReceiptNumber();
        $newReceipt->email = $this->oldReceipt->email;
        $newReceipt->date_paid = $this->newReceiptForm->datePaid;

        $newReceipt->cheque_number =
            ($this->newReceiptForm->chequeNumber == true) ?
            $this->newReceiptForm->chequeNumber : null;

        $newReceipt->timestamp = date("Y-m-d H:i:s");
        if ($newReceipt->validate() == false) {
            return false;
        } else {
            return $newReceipt;
        }
    }


    private function generateNewBilling($billingForm, $receipt, $billingCharge)
    {
        $billing = new Billing();
        // $billing->receipt_id = $receipt->id;
        $billing->billing_charge_id = $billingCharge->id;
        $billing->customer_id = $receipt->customer_id;
        $billing->application_period_id = $billingCharge->application_period_id;
        $billing->student_registration_id = $receipt->student_registration_id;
        $billing->academic_offering_id = $billingCharge->academic_offering_id;
        $billing->created_by = $receipt->created_by;
        $billing->created_at = $receipt->timestamp;
        $billing->cost = $billingForm->balance;
        $billing->amount_paid = $billingForm->amountPaid;
        return $billing;
    }


    private function generateNewBillings($receipt)
    {
        $newBillings = array();
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
                $newBillings[] = $billing;
            }
        }
        return $newBillings;
    }


    private function saveNewReceipt($receipt)
    {
        // if ($receipt->save() == true) {
        //     return $receipt;
        // }
        // return false;
        return $receipt->save();
    }


    private function saveOldReceipt()
    {
        return $this->oldReceipt->save();
    }


    private function saveNewBillings($billings, $receipt)
    {
        foreach ($billings as $billing) {
            $billing->receipt_id = $receipt->id;
            if ($billing->save() == false) {
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


    private function processStudentCredits($newReceipt)
    {
        $oldReceiptTotal = ReceiptModel::calculateReceiptTotal($this->oldReceipt);
        $newReceiptTotal = ReceiptModel::calculateReceiptTotal($newReceipt);

        if ($oldReceiptTotal > $newReceiptTotal) {
            $creditDue = $oldReceiptTotal - $newReceiptTotal;

            $studentRegistration =
                StudentRegistrationModel::getStudentRegistrationByID(
                    $this->oldReceipt->student_registration_id
                );

            $studentRegistration->credit_amount = $creditDue;
            return $studentRegistration->save();
        }
        return true;
    }


    public function execute()
    {
        $newReceipt = $this->generateNewReceipt();
        $newBillings = $this->generateNewBillings($newReceipt);
        $this->voidOldReceipt($newReceipt->receipt_number);
        $this->voidOldBillings();

        if (!($newReceipt instanceof Receipt)) {
            return new ErrorObject("Error ocurred generating receipt");
        } elseif (empty($newBillings)) {
            return new ErrorObject("Error ocurred generating billings");
        } elseif ($this->saveNewReceipt($newReceipt) == false) {
            return new ErrorObject("Error ocurred saving receipt");
        } elseif ($this->saveNewBillings($newBillings, $newReceipt) == false) {
            return new ErrorObject("Error ocurred saving billing");
        } elseif ($this->saveOldReceipt() == false) {
            return new ErrorObject("Error ocurred void old receipt");
        } elseif ($this->saveOldBillings() == false) {
            return new ErrorObject("Error ocurred void old billings");
        } elseif ($this->processStudentCredits($newReceipt) == false) {
            return new ErrorObject("Error ocurred updating credits");
        } else {
            return $newReceipt;
        }
    }
}
