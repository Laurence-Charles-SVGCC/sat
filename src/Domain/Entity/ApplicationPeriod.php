<?php

namespace src\Domain\Entity;

class ApplicationPeriod extends AbstractEntity
{
    protected $applicationPeriodStatus;
    protected $division;
    protected $academicYear;
    protected $applicationPeriodType;
    protected $name;
    protected $startDate;
    protected $endDate;
    protected $isComplete;
    protected $catalogApproved;
    protected $programmesAdded;
    protected $capeSubjectsAdded;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getApplicationPeriodStatus(): ApplicationPeriodStatus
    {
        return $this->applicationPeriodStatus;
    }

    public function setApplicationPeriodStatus(ApplicationPeriodStatus $applicationPeriodStatus): ApplicationPeriod
    {
        $this->applicationPeriodStatus = $applicationPeriodStatus;
        return $this;
    }

    public function getDivision(): Division
    {
        return $this->division;
    }

    public function setDivision(Division $division): ApplicationPeriod
    {
        $this->division = $division;
        return $this;
    }

    public function getAcademicYear(): AcademicYear
    {
        return $this->academicYear;
    }

    public function setAcademicYear(AcademicYear $academicYear): ApplicationPeriod
    {
        $this->academicYear = $academicYear;
        return $this;
    }

    public function getApplicationPeriodType(): ApplicationPeriodType
    {
        return $this->applicationPeriodType;
    }

    public function setApplicationPeriodType(ApplicationPeriodType $applicationPeriodType): ApplicationPeriod
    {
        $this->applicationPeriodType = $applicationPeriodType;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ApplicationPeriod
    {
        $this->name = $name;
        return $this;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): ApplicationPeriod
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): ApplicationPeriod
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getIsComplete(): int
    {
        return $this->isComplete;
    }

    public function setIsComplete(int $isComplete): ApplicationPeriod
    {
        $this->isComplete = $isComplete;
        return $this;
    }

    public function getCatalogApproved(): int
    {
        return $this->catalogApproved;
    }

    public function setCatalogApproved(int $catalogApproved): ApplicationPeriod
    {
        $this->catalogApproved = $catalogApproved;
        return $this;
    }

    public function getProgrammesAdded(): int
    {
        return $this->programmesAdded;
    }

    public function setProgrammesAdded(int $programmesAdded): ApplicationPeriod
    {
        $this->programmesAdded = $programmesAdded;
        return $this;
    }

    public function getCapSubjectsAdded(): int
    {
        return $this->capSubjectsAdded;
    }

    public function setCapSubjectsAdded(int $capSubjectsAdded): ApplicationPeriod
    {
        $this->capSubjectsAdded = $capSubjectsAdded;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): ApplicationPeriod
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): ApplicationPeriod
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): ApplicationPeriod
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): ApplicationPeriod
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): ApplicationPeriod
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): ApplicationPeriod
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
