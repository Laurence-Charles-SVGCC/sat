<?php

namespace src\Domain\Entity;

class AcademicOffering extends AbstractEntity
{
    protected $programmeCatalog;
    protected $academicYear;
    protected $applicationPeriod;
    protected $spaces;
    protected $interviewNeeded;
    protected $graduationCreditRequirements;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getProgrammeCatalog(): ProgrammeCatalog
    {
        return $this->programmeCatalog;
    }

    public function setProgrammeCatalog(ProgrammeCatalog $programmeCatalog): AcademicOffering
    {
        $this->programmeCatalog = $programmeCatalog;
        return $this;
    }

    public function getAcademicYear(): AcademicYear
    {
        return $this->academicYear;
    }

    public function setAcademicYear(AcademicYear $academicYear): AcademicOffering
    {
        $this->academicYear = $academicYear;
        return $this;
    }

    public function getApplicationPeriod(): ApplicationPeriod
    {
        return $this->applicationPeriod;
    }

    public function setApplicationPeriod(ApplicationPeriod $applicationPeriod): AcademicOffering
    {
        $this->applicationPeriod = $applicationPeriod;
        return $this;
    }

    public function getSpaces(): int
    {
        return $this->spaces;
    }

    public function setSpaces(int $spaces): AcademicOffering
    {
        $this->spaces = $spaces;
        return $this;
    }

    public function getGraduationCreditRequirements(): int
    {
        return $this->graduationCreditRequirements;
    }

    public function setGraduationCreditRequirements(int $graduationCreditRequirements): AcademicOffering
    {
        $this->graduationCreditRequirements = $graduationCreditRequirements;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): AcademicOffering
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): AcademicOffering
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): AcademicOffering
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): AcademicOffering
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): AcademicOffering
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): AcademicOffering
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
