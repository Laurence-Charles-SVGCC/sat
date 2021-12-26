<?php

namespace src\Domain\Entity;

class AcademicYear extends AbstractEntity
{
    protected $title;
    protected $applicantIntent;
    protected $isCurrent;
    protected $startDate;
    protected $endDate;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): AcademicYear
    {
        $this->title = $title;
        return $this;
    }

    public function getApplicantIntent(): ApplicantIntent
    {
        return $this->applicantIntent;
    }

    public function setApplicantIntent(ApplicantIntent $applicantIntent): AcademicYear
    {
        $this->applicantIntent = $applicantIntent;
        return $this;
    }

    public function getIsCurrent(): int
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(int $isCurrent): AcademicYear
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): AcademicYear
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): AcademicYear
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): AcademicYear
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): AcademicYear
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): AcademicYear
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): AcademicYear
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): AcademicYear
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): AcademicYear
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
