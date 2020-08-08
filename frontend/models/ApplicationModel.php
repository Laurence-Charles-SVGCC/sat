<?php

namespace frontend\models;

class ApplicationModel
{
    public static function getApplicationsForActivePeriodByPersondId($id)
    {
        return Application::find()
        ->innerJoin(
            'academic_offering',
            '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`'
        )
        ->innerJoin(
            'application_period',
            '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`'
        )
        ->where(
            [
                'application_period.iscomplete' => 0,
                'application_period.isactive' => 1,
                'application_period.isdeleted' => 0,
                'application.isactive' => 1,
                'application.isdeleted' => 0,
                'application.personid' => $id
            ]
        )
        ->orderBy('application.ordering ASC')
        ->all();
    }
}
