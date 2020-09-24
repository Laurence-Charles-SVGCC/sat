<?php

namespace frontend\models;

class AcademicOfferingModel
{
    public static function getAcademicOfferingById($id)
    {
        return AcademicOffering::find()
        ->where(['academicofferingid' => $id])
        ->one();
    }


    public static function requiresInterview($applicationid)
    {
        $record =
        AcademicOffering::find()
        ->innerJoin(
            'application',
            '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`'
        )
        ->where([
          'academic_offering.isactive' => 1,
          'academic_offering.isdeleted' => 0,
          'academic_offering.interviewneeded' => 1,
          'application.isactive' => 1,
          'application.isdeleted' => 0,
          'application.applicationid' => $applicationid,
        ])
        ->one();
        
        if ($record) {
            return true;
        }
        return false;
    }
}
