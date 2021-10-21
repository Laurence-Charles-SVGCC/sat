<?php

namespace common\models;

use yii\base\Model;

class BatchStudentFeePaymentForm extends Model
{
    public $username;
    public $fullName;
    public $paymentMethodId;
    public $receiptNumber;
    public $customerId;
    public $staffId;
    public $applicationPeriodId;
    public $datePaid;
    public $autoPublish;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    "username",
                    "fullName",
                    "paymentMethodId",
                    "datePaid",
                    "autoPublish",
                    "receiptNumber",
                    "customerId",
                    "staffId",
                    "applicationPeriodId",
                ],
                "required"
            ],
            [["receiptNumber", "username", "fullName"], "string"],
            [
                [
                    "paymentMethodId",
                    "autoPublish",
                    "customerId",
                    "staffId",
                    "applicationPeriodId"
                ],
                "integer"
            ],
            [["datePaid"], "safe"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            "username" => "ApplicantID",
            "fullName" => "Full Name",
            "paymentMethodId" => "Payment Method",
            "receiptNumber" => "Receipt Number",
            "datePaid" => "Date of payment",
            "autoPublish" => "Email invoice to applicant"
        ];
    }


    public function fillModel(
        $customer,
        $staffId,
        $userFullname,
        $applicationPeriodId
    ) {
        $this->username = $customer->username;
        $this->fullName = $userFullname;
        $this->customerId = $customer->personid;
        $this->staffId = $staffId;
        $this->applicationPeriodId = $applicationPeriodId;
        $this->autoPublish = 1;
    }


    public function generateDefaultBillingFormsForSuccessfulApplicant(
        $academicOffering
    ) {
        $records = array();

        $applicableBillingCharges =
            BillingChargeModel::getOutstandingEnrollmentChargesByApplicationPeriodId(
                $academicOffering,
                $this->customerId
            );

        foreach ($applicableBillingCharges as $billingCharge) {
            $record = new BatchStudentFeePaymentBillingForm();
            $record->fillModel($this->customerId, $billingCharge);
            $records[] = $record;
        }

        return $records;
    }


    private function getCustomerStudentRegistration(
        $personId,
        $applicationPeriodId
    ) {
        $regustrations =  StudentRegistration::find()
            ->innerJoin(
                'academic_offering',
                '`student_registration`.`academicofferingid` = `academic_offering`.`academicofferingid`'
            )
            ->where([
                "academic_offering.applicationperiodid" => $applicationPeriodId,
                "student_registration.personid" => $personId,
                "student_registration.isdeleted" => 0
            ])
            ->all();
        if (!empty($registrations)) {
            return $registrations[0];
        } else {
            return false;
        }
    }


    private function generateReceipt()
    {
        $receipt = new Receipt();
        $receipt->payment_method_id = $this->paymentMethodId;
        $receipt->customer_id = $this->customerId;

        $studentRegistration =
            $this->getCustomerStudentRegistration(
                $this->customerId,
                $this->applicationPeriodId
            );
        if ($studentRegistration == true) {
            $receipt->student_registration_id =
                $studentRegistration->student_registration_id;
        }
        $receipt->created_by = $this->staffId;
        $receipt->username = $this->username;
        $receipt->full_name = $this->fullName;
        $receipt->receipt_number = $this->receiptNumber;

        $receipt->email =
            EmailModel::getEmailByPersonid($this->customerId)->email;

        $receipt->date_paid = $this->datePaid;
        $receipt->auto_publish = $this->autoPublish;
        $receipt->timestamp = date("Y-m-d H:i:s");
        if ($receipt->save() == true) {
            return $receipt;
        } else {
            return null;
        }
    }


    /**
     * Process user billing input to generate billing models
     *
     * @param Receipt $receipt
     * @param array BatchStudentFeePaymentBillingForm $billings
     * @return array Billings
     * 
     * Test command:
     * Untested
     */
    public function generateBillings($receipt, $billings)
    {
        $savedBillings = array();
        if ($billings == false) {
            return false;
        } else {
            foreach ($billings as $billing) {
                if ($billing->validateModel() == true) {
                    $billingModel = $this->generateBilling($receipt, $billing);
                    if ($billingModel == false) {
                        return false;
                    } else {
                        $savedBillings[] = $billingModel;
                    }
                }
            }
        }
        return $savedBillings;
    }


    /**
     * Generate Billing model
     *
     * @param Receipt $receiptId
     * @param BatchStudentFeePaymentBillingForm $billing
     * @return Billing|null
     * 
     * Test command:
     * Untested
     */
    private function generateBilling($receipt, $billing)
    {
        $billingModel = new Billing();
        $billingModel->receipt_id = $receipt->id;
        $billingModel->billing_charge_id = $billing->billingChargeId;
        $billingModel->customer_id = $this->customerId;
        $billingModel->application_period_id = $this->applicationPeriodId;
        $billingModel->created_by = $this->staffId;
        $billingModel->cost = $billing->balance;
        $billingModel->amount_paid = $billing->amountPaid;

        if ($receipt->student_registration_id == true) {
            $billingModel->student_registration_id =
                $receipt->student_registration_id;

            $studentRegistration =
                StudentRegistrationModel::getStudentRegistrationByID(
                    $receipt->student_registration_id
                );
            $billingModel->academic_offering_id =
                $studentRegistration->academicofferingid;
        }

        if ($billingModel->save() == true) {
            return $billingModel;
        } else {
            return null;
        }
    }

    private function billingsSubmitted($billings)
    {
        if ($billings == true) {
            foreach ($billings as $billing) {
                if ($billing->isSelected() == true) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Generates receipt and billing for enrollment fee payment process
     *
     * @param array BatchStudentFeePaymentBillingForm $billings
     * @param Controller $controller
     * @return Receipt|ErrorObject
     * 
     * Test command:
     * Untested
     */
    public function processSuccessfulApplicantPaymentRequest(
        $billings,
        $controller
    ) {
        if ($this->billingsSubmitted($billings) == false) {
            return new ErrorObject("No fees were selected for payment.");
        } else {
            $receipt = $this->generateReceipt();

            if ($receipt == null) {
                return new ErrorObject("Error ocurred generating receipt");
            } else {
                $billings = $this->generateBillings($receipt, $billings);

                if ($billings == false) {
                    return new ErrorObject("Error ocurred generating billings.");
                } else {
                    // $billings = $receipt->getBillings()->all();
                    $customer = UserModel::getUserById($receipt->customer_id);
                    $applicantName = UserModel::getUserFullname($customer);
                    $applicantId = $customer->username;
                    if ($this->autoPublish == true) {
                        ReceiptModel::publishReceipt(
                            $controller,
                            $receipt,
                            $billings,
                            $applicantName,
                            $applicantId
                        );
                    }
                    return $receipt;
                }
            }
        }
    }


    public function generateDefaultBillingFormsForStudent(
        $studentRegistration
    ) {
        $records = array();

        $applicableBillingCharges =
            BillingChargeModel::getAllOutstandingBillingCharges(
                $studentRegistration
            );

        foreach ($applicableBillingCharges as $billingCharge) {
            $record = new BatchStudentFeePaymentBillingForm();
            $record->fillModel($this->customerId, $billingCharge);
            $records[] = $record;
        }

        return $records;
    }


    public function processEnrolledStudentPaymentRequest(
        $billings,
        $controller,
        $studentRegistrationId
    ) {
        if ($this->billingsSubmitted($billings) == false) {
            return new ErrorObject("No fees were selected for payment.");
        } else {
            $receipt = $this->generateEnrolledStudentReceipt(
                $studentRegistrationId
            );

            if ($receipt == null) {
                return new ErrorObject("Error ocurred generating receipt");
            } else {
                $billings = $this->generateBillings($receipt, $billings);

                if ($billings == false) {
                    return new ErrorObject("Error ocurred generating billings.");
                } else {
                    if ($this->autoPublish == true) {
                        $customer = UserModel::getUserById($receipt->customer_id);
                        $applicantName = UserModel::getUserFullname($customer);
                        $applicantId = $customer->username;
                        ReceiptModel::publishReceipt(
                            $controller,
                            $receipt,
                            $billings,
                            $applicantName,
                            $applicantId
                        );
                    }
                    return $receipt;
                }
            }
        }
    }


    private function generateEnrolledStudentReceipt($studentRegistrationId)
    {
        $receipt = new Receipt();
        $receipt->payment_method_id = $this->paymentMethodId;
        $receipt->customer_id = $this->customerId;
        $receipt->student_registration_id = $studentRegistrationId;
        $receipt->created_by = $this->staffId;
        $receipt->username = $this->username;
        $receipt->full_name = $this->fullName;
        $receipt->receipt_number = $this->receiptNumber;

        $receipt->email =
            EmailModel::getEmailByPersonid($this->customerId)->email;

        $receipt->date_paid = $this->datePaid;
        $receipt->auto_publish = $this->autoPublish;
        $receipt->timestamp = date("Y-m-d H:i:s");
        if ($receipt->save() == true) {
            return $receipt;
        } else {
            return null;
        }
    }
}
