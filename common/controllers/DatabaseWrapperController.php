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
                    ->leftjoin('csec_qualification', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('application', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['application_period.isactive' => 1, 'csec_centre.isdeleted' => 0])->all();
        
        return $centres;
    }
    
     /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 15/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsReceived($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'application_period.isactive' => 1, 'application.isdeleted' => 0])
                    ->groupby('application.personid')->all();
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
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isverified' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate flagged as to be queried
    * Created: 20/07/2015 by Gamal Crichton
    * Last Modified: 20/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsQueried($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isqueried' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        return $applicants;
    }
    
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsReceivedCount($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'application_period.isactive' => 1, 'application.isdeleted' => 0])
                    ->groupby('application.personid')->count();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsVerifiedCount($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isverified' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0])
                    ->groupby('application.personid')->count();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate flagged as to be queried
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 16/07/2015 by Gamal Crichton
    */
    public static function centreApplicantsQueriedCount($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isqueried' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0])
                    ->groupby('application.personid')->count();
        return $applicants;
    }

}

