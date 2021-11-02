<?php

namespace common\models;

class AcademicInstitutionAttendance
{
    public $person;
    public $institutionName;
    public $institutionLevel;
    public $yearOfGraduation;

    public function __construct(
        $person,
        $institutionName,
        $institutionLevel,
        $yearOfGraduation
    ) {
        $this->person = $person;
        $this->institutionName = $institutionName;
        $this->institutionLevel = $institutionLevel;
        $this->yearOfGraduation = $yearOfGraduation;
    }
}
