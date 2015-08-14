<?php
namespace common\controllers;


use yii\web\Controller;
use frontend\models\CsecCentre;
use frontend\models\Application;
use frontend\models\CsecQualification;

use backend\models\PersonType;


/**
 * Site controller
 */
class DatabaseWrapperController extends Controller
{
    
    public static function getPersonTypeID($person_name)
    {
        $person_type = PersonType::find()->where(['persontype' => $person_name])->one();
        return $person_type ? $person_type->persontypeid : 3;
    }
    
    /*
    * Purpose: Gets the CSEC Centres relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function getCurrentCentres()
    {
        $centres = CsecCentre::find()
                    ->leftjoin('csec_qualification', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('application', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['application_period.isactive' => 1, 'csec_centre.isdeleted' => 0, 'application.isdeleted' => 0,
                        'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])->all();
        
        return $centres;
    }
    
     /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsReceived($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'application_period.isactive' => 1, 'application.isdeleted' => 0,
                        'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 15/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsVerified($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isverified' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0, 'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        foreach ($applicants as $key => $applicant)
        {
            if (CsecQualification::findOne(['personid' => $applicant->personid, 'isverified' => 0]))
            {
                unset($applicants[$key]);
            }
        }
        return $applicants;
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate flagged as to be queried
    * Created: 20/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsQueried($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isqueried' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0, 'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate that are not flagged as yet
    * Created: 14/08/2015 by Gamal Crichton
    * Last Modified: 14/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsPending($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isqueried' => 0, 'csec_qualification.isverified' => 0,
                        'application_period.isactive' => 1, 'application.isdeleted' => 0, 'csec_qualification.isdeleted' => 0, 
                        'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->all();
        return $applicants;
    }
    
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsReceivedCount($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'application_period.isactive' => 1, 'application.isdeleted' => 0,
                        'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->count();
        return $applicants;
    }
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have already been fully verified
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsVerifiedCount($cseccentreid)
    {
        return count(self::centreApplicantsVerified($cseccentreid));
    }
    
    /*
    * Purpose: Gets the count of Applicants with CSEC Certificates to a particular CSEC Centre relevant to active application periods
     *          who have a certificate flagged as to be queried
    * Created: 16/07/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
    public static function centreApplicantsQueriedCount($cseccentreid)
    {
        $applicants = Application::find()
                    ->leftjoin('csec_qualification', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->leftjoin('csec_centre', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->leftjoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->leftjoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['csec_centre.cseccentreid' => $cseccentreid, 'csec_qualification.isqueried' => 1, 'application_period.isactive' => 1,
                        'application.isdeleted' => 0, 'csec_qualification.isdeleted' => 0, 'academic_offering.isdeleted' => 0])
                    ->groupby('application.personid')->count();
        return $applicants;
    }
    
    /*
    * Purpose: Gets counts of the Applications to a particular Division relevant to active application periods
     *          who have already been fully verified
    * Created: 23/07/2015 by Gamal Crichton
    * Last Modified: 23/07/2015 by Gamal Crichton
    */
    public static function divisionApplicationsReceivedCount($division_id, $order)
    {
        return Application::find()
                ->joinWith('academic_offering')
                ->joinwith('application_period')
                ->where(['application_period.divisionid' => $division_id, 'application.ordering' => $order])
                ->count();
    }

}

