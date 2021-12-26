<?php

namespace src\Domain\Entity;

class PaymentMethod extends AbstractEntity
{
    protected $name;
    protected $creator;
    protected $modifier;
    protected $isActive;
    protected $isDeleted;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(int $name): PaymentMethod
    {
        $this->name = $name;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): PaymentMethod
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): PaymentMethod
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): PaymentMethod
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): PaymentMethod
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
