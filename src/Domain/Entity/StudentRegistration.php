<?php

namespace src\Domain\Entity;

class StudentRegistration extends AbstractEntity
{
    protected $academicStatus;
    protected $registrationType;
    protected $idCardStatus;
    protected $currentLevel;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getAcademicStatus(): AcademicStatus
    {
        return $this->academicStatus;
    }

    public function setAcademicStatus(AcademicStatus $academicStatus): StudentRegistration
    {
        $this->academicStatus = $academicStatus;
        return $this;
    }

    public function getRegistrationType(): RegistrationType
    {
        return $this->registrationType;
    }

    public function setRegistrationType(RegistrationType $registrationType): StudentRegistration
    {
        $this->registrationType = $registrationType;
        return $this;
    }

    public function getIdCardStatus(): IdCardStatus
    {
        return $this->idCardStatus;
    }

    public function setIdCardStatus(IdCardStatus $idCardStatus): StudentRegistration
    {
        $this->idCardStatus = $idCardStatus;
        return $this;
    }

    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }

    public function setCurrentLevel(int $currentLevel): StudentRegistration
    {
        $this->currentLevel = $currentLevel;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): StudentRegistration
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): StudentRegistration
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): StudentRegistration
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): StudentRegistration
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): StudentRegistration
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): StudentRegistration
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
