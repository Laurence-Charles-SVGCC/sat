<?php

namespace src\Domain\Entity;

class BillingCategory extends AbstractEntity
{
    protected $name;
    protected $billingScope;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isDeleted;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): BillingCategory
    {
        $this->name = $name;
        return $this;
    }

    public function getBillingScope(): BillingScope
    {
        return $this->billingScope;
    }

    public function setBillingScope(BillingScope $billingScope): BillingCategory
    {
        $this->billingScope = $billingScope;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): BillingCategory
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): BillingCategory
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): BillingCategory
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): BillingCategory
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): BillingCategory
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): BillingCategory
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
