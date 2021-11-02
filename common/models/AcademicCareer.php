<?php

namespace common\models;

class AcademicCareer
{
    private $person;
    private $secondaryAttendances;

    public function __construct($person)
    {
        $this->person = $person;
        $this->secondaryAttendances = array();
    }

    private function getSecondaryAttendanceRecords()
    {
        return PersonInstitution::find()
            ->innerJoin(
                'institution',
                '`person_institution`.`institutionid` = `institution`.`institutionid`'
            )
            ->where([
                "person_institution.personid" => $this->person->personid,
                "person_institution.isactive" => 1,
                "person_institution.isdeleted" => 0,
                "institution.levelid" => 3
            ])
            ->all();
    }


    public function getSecondaryAttendances()
    {
        $person_institutions = $this->getSecondaryAttendanceRecords();
        if (!empty($person_institutions)) {
            foreach ($person_institutions as $record) {
                $institution =
                    Institution::find()
                    ->where(["institutionid" => $record->institutionid])
                    ->one();

                $attendance =
                    new AcademicInstitutionAttendance(
                        $this->person,
                        $institution->name,
                        "Secondary School",
                        $record->year_of_graduation
                    );

                array_push($this->secondaryAttendances, $attendance);
            }
        }
        return $this->secondaryAttendances;
    }
}
