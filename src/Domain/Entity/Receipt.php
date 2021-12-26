<?php

namespace src\Domain\Entity;

class Receipt extends AbstractEntity
{
    protected $paymentMethod;
    protected $customer;
    protected $billings;
    protected $receiptNumber;
    protected $chequeNumber;
    protected $notes;
    protected $numberOfTimesPublished;
    protected $autoPublish;
    protected $datePaid;
    protected $creator;
    protected $modifier;
    protected $createdTimeStamp;
    protected $modifiedTimeStamp;
    protected $isActive;
    protected $isDeleted;

    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): Receipt
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): Receipt
    {
        $this->customer = $customer;
        return $this;
    }

    public function getBillings(): array
    {
        return $this->billings;
    }

    public function appendBilling(Billing $billing): array
    {
        array_push($this->billings, $billing);
        return $this->billings;
    }

    public function getReceiptNumber(): int
    {
        return $this->receiptNumber;
    }

    public function setReceiptNumber(int $receiptNumber): Receipt
    {
        $this->receiptNumber = $receiptNumber;
        return $this;
    }

    public function getChequeNumber(): int
    {
        return $this->chequeNumber;
    }

    public function setChequeNumber(int $chequeNumber): Receipt
    {
        $this->chequeNumber = $chequeNumber;
        return $this;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): Receipt
    {
        $this->notes = $notes;
        return $this;
    }

    public function getNumberOfTimesPublished(): int
    {
        return $this->numberOfTimesPublished;
    }

    public function setNumberOfTimesPublished(int $numberOfTimesPublished): Receipt
    {
        $this->numberOfTimesPublished = $numberOfTimesPublished;
        return $this;
    }

    public function getAutoPublish(): int
    {
        return $this->autoPublish;
    }

    public function setAutoPublished(int $autoPublish): Receipt
    {
        $this->autoPublish = $autoPublish;
        return $this;
    }

    public function getDatePaid(): \DateTime
    {
        return $this->datePaid;
    }

    public function setDatePaid(\DateTime $datePaid): Receipt
    {
        $this->datePaid = $datePaid;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): Receipt
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): Receipt
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): Receipt
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): Receipt
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): Receipt
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): Receipt
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
