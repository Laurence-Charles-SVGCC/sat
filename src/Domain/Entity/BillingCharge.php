<?php

namespace src\Domain\Entity;

class BillingCharge extends AbstractEntity
{
    protected $billingType;
    protected $applicationPeriod;
    protected $academicOffering;
    protected $cost;
    protected $payableOnEnrollment;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getAcademicOffering(): AcademicOffering
    {
        return $this->acadmeicOffering;
    }

    public function setAcademicOffering(AcademicOffering $academicOffering): BillingCharge
    {
        $this->academicOffering = $academicOffering;
        return $this;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(int $cost): BillingCharge
    {
        $this->cost = $cost;
        return $this;
    }

    public function getPayableOnEnrollment(): int
    {
        return $this->payableOnEnrollment;
    }

    public function setPayableOnEnrollment(int $payableOnEnrollment): BillingCharge
    {
        $this->payableOnEnrollment = $payableOnEnrollment;
        return $this;
    }


    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): BillingCharge
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): BillingCharge
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): BillingCharge
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): BillingCharge
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): BillingCharge
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
