<?php

namespace src\Domain\Entity;

class ProgrammeCatalog extends AbstractEntity
{
    protected $programmeType;
    protected $examinationBody;
    protected $qualificationType;
    protected $department;
    protected $creationDate;
    protected $specialisation;
    protected $duration;
    protected $name;
    protected $graduationCreditRequirements;
    protected $creator;
    protected $modifier;
    protected $createdTimestamp;
    protected $modifiedTimestamp;
    protected $isActive;
    protected $isDeleted;

    public function getProgrammeType(): IntentType
    {
        return $this->programmeType;
    }

    public function setProgrammeType(IntentType $programmeType): ProgrammeCatalog
    {
        $this->programmeType = $programmeType;
        return $this;
    }

    public function getExaminationBody(): ExaminationBody
    {
        return $this->examinationBody;
    }

    public function setExaminationBody(ExaminationBody $examinationBody): ProgrammeCatalog
    {
        $this->examinationBody = $examinationBody;
        return $this;
    }

    public function getQualificationType(): QualificationType
    {
        return $this->qualificationType;
    }

    public function setQualificationType(QualificationType $qualificationType): ProgrammeCatalog
    {
        $this->qualificationType = $qualificationType;
        return $this;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): ProgrammeCatalog
    {
        $this->department = $department;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): ProgrammeCatalog
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getSpecialisation(): string
    {
        return $this->specialisation;
    }

    public function setSpecialisation(string $specialisation): ProgrammeCatalog
    {
        $this->specialisation = $specialisation;
        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): ProgrammeCatalog
    {
        $this->duration = $duration;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProgrammeCatalog
    {
        $this->name = $name;
        return $this;
    }

    public function getGraduationCreditRequirements(): int
    {
        return $this->graduationCreditRequirements;
    }

    public function setGraduationCreditRequirements(int $graduationCreditRequirements): ProgrammeCatalog
    {
        $this->graduationCreditRequirements = $graduationCreditRequirements;
        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): ProgrammeCatalog
    {
        $this->creator = $creator;
        return $this;
    }

    public function getModifier(): User
    {
        return $this->modifier;
    }

    public function setModifier(User $modifier): ProgrammeCatalog
    {
        $this->creator = $modifier;
        return $this;
    }

    public function getCreatedTimeStamp(): \DateTime
    {
        return $this->createdTimeStamp;
    }

    public function setCreatedTimeStamp(\DateTime $createdTimeStamp): ProgrammeCatalog
    {
        $this->createdTimeStamp = $createdTimeStamp;
        return $this;
    }

    public function getModifiedTimeStamp(): \DateTime
    {
        return $this->modifiedTimeStamp;
    }

    public function setModifiedTimeStamp(\DateTime $modifiedTimeStamp): ProgrammeCatalog
    {
        $this->modifiedTimeStamp = $modifiedTimeStamp;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): ProgrammeCatalog
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): ProgrammeCatalog
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
