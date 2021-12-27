<?php

namespace src\Domain\Entity;

class Billing extends AbstractEntity
{
    protected $receipt;
    protected $billingCharge;
    protected $customer;
    protected $studentRegistration;
    protected $academicOffering;
    protected $applicationPeriod;
    protected $cost;
    protected $amountPaid;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getReceipt(): Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(Receipt $receipt): Billing
    {
        $this->receipt = $receipt;
        return $this;
    }

    public function getBillingCharge(): BillingCharge
    {
        return $this->billingCharge;
    }

    public function setBillingCharge(BillingCharge $billingCharge): Billing
    {
        $this->billingCharge = $billingCharge;
        return $this;
    }

    public function getCustomer(): User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): Billing
    {
        $this->customer = $customer;
        return $this;
    }

    public function getStudentRegistration(): StudentRegistration
    {
        return $this->studentRegistration;
    }

    public function setStudentRegistration(StudentRegistration $studentRegistration): Billing
    {
        $this->studentRegistration = $studentRegistration;
        return $this;
    }

    public function getAcademicOffering(): AcademicOffering
    {
        return $this->academicOffering;
    }

    public function setAcademicOffering(AcademicOffering $academicOffering): Billing
    {
        $this->academicOffering = $academicOffering;
        return $this;
    }

    public function getApplicationPeriod(): ApplicationPeriod
    {
        return $this->applicationPeriod;
    }

    public function setApplicationPeriod(ApplicationPeriod $applicationPeriod): Billing
    {
        $this->applicationPeriod = $applicationPeriod;
        return $this;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): Billing
    {
        $this->cost = $cost;
        return $this;
    }

    public function getAmountPaid(): float
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(float $amountPaid): Billing
    {
        $this->amountPaid = $amountPaid;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): Billing
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): Billing
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): Billing
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): Billing
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): Billing
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): Billing
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
