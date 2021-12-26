<?php

namespace src\Domain\Entity;

class Customer extends AbstractEntity
{
    protected $user;
    protected $displayName;
    protected $title;
    protected $firstName;
    protected $middlenames;
    protected $lastname;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Customer
    {
        $this->user = $user;
        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): Customer
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Customer
    {
        $this->title = $title;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Customer
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getMiddleNames(): string
    {
        return $this->middlenames;
    }

    public function setMiddleName(string $middleNames): Customer
    {
        $this->middleNames = $middleNames;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Customer
    {
        $this->lastNames = $lastName;
        return $this;
    }
}
