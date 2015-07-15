<?php
namespace common\controllers;


use yii\web\Controller;
use frontend\models\CsecCentre;
use frontend\models\Application;


/**
 * Site controller
 */
class DatabaseWrapperController extends Controller
{
    
    public static function getPersonTypeID($person_name)
    {
        return 0;
    }
    
    /*
    * Purpose: Gets the CSEC Centres relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    public static function getCurrentCentres()
    {
        $centres = CsecCentre::find()
                    ->join('inner', 'csec_qualification', '`cseccentreid` = `cseccentreid`')
                    ->join('inner', 'application', '`personid` = `personid`')
                    ->join('inner', 'academic_offering', '`academicofferingid` = `academicofferingid`')
                    ->join('inner', 'application_period', '`applicationperiodid` = `applicationperiodid`')
                    ->where(['`application_period`.`isactive`' => 1])->all();
        
        return $centres;
    }
    
     /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    public static function getCentreApplicantsReceived($cseccentreid)
    {
        $applicants = Application::find()
                    ->join('inner', 'csec_qualification', '`personid` = `personid`')
                    ->join('inner', 'csec_centre', 'cseccentreid = cseccentreid')
                    ->join('inner', 'academic_offering', 'academicofferingid = academicofferingid')
                    ->join('inner', 'application_period', 'applicationperiodid = applicationperiodid')
                    ->where("cseccentreid = $cseccentreid and application_period.isactive = 1")
                    ->groupby('personid')->all();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsVerified($cseccentreid)
    {
        $applicants = Application::find()
                    ->join('inner', 'csec_qualification', 'personid = personid')
                    ->join('inner', 'csec_centre', 'cseccentreid = cseccentreid')
                    ->join('inner', 'academic_offering', 'academicofferingid = academicofferingid')
                    ->join('inner', 'application_period', 'applicationperiodid = applicationperiodid')
                    ->where("cseccentreid = $cseccentreid and isverified = 1 and application_period.isactive = 1")
                    ->groupby('personid')->all();
        return $applicants;
    }

}

