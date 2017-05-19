<?php

namespace frontend\models;

use Yii;

use frontend\models\Application;
use frontend\models\Address;
use frontend\models\Phone;
use common\models\User;
use frontend\models\OfferType;
use frontend\models\Applicant;

/**
 * This is the model class for table "offer".
 *
 * @property string $offerid
 * @property string $applicationid
 * @property string $offertypeid
 * @property string $issuedby
 * @property string $issuedate
 * @property string $revokedby
 * @property string $revokedate
 * @property integer $ispublished
 * @property interger $isactive
 * @property integer $isdeleted
 * @property integer $packageid
 * @property integer $appointment
 *
 * @property Application $application
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationid', 'issuedby', 'issuedate', ], 'required'],
            [['applicationid', 'offertypeid', 'issuedby', 'revokedby', 'ispublished', 'isactive', 'isdeleted', 'packageid'], 'integer'],
            [['issuedate', 'revokedate', 'appointment'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerid' => 'Offerid',
            'applicationid' => 'Applicationid',
            'offertypeid' => 'Offertypeid',
            'issuedby' => 'Issuedby',
            'issuedate' => 'Issuedate',
            'revokedby' => 'Revokedby',
            'revokedate' => 'Revokedate',
            'ispublished' => 'Ispublished',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'packageid' => 'Packageid',
            'appointment' => 'Appointment'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['applicationid' => 'applicationid']);
    }
    
    
    /**
     * Returns the most current offer information
     * 
     * @param type $studentregistrationid
     * 
     * Author: Laurence Charles
     * Date Created: 28/02/2016
     * Date Last Modified: 28/02/2016
     */
    public static function getCurrentOffer($studentregistrationid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT application.applicationid AS 'applicationid',"
                . " application.academicofferingid AS 'academicofferingid',"
                . " application.ordering AS 'ordering',"
                . " offer_type.name AS 'offertype',"
                . " programme_catalog.name AS 'name',"
                . " offer.issuedby AS 'issuedby',"
                . " offer.issuedate AS 'issuedate',"
                . " offer.revokedby AS 'revokedby',"
                . " offer.revokedate AS 'revokedate'"
                . " FROM student_registration"
                . " JOIN offer"
                . " ON student_registration.offerid = offer.offerid"
                . " JOIN application"
                . " ON offer.applicationid = application.applicationid"
                . " JOIN academic_offering"
                . " ON application.academicofferingid = academic_offering.academicofferingid"
                . " JOIN programme_catalog"
                . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                . " JOIN offer_type"
                . " ON offer.offertypeid = offer_type.offertypeid"
                . " WHERE student_registration.registrationid = " . $studentregistrationid
                . ";"
                )
                ->queryAll();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    public static function getOffers($personid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT application.applicationid AS 'applicationid',"
                . " application.academicofferingid AS 'academicofferingid',"
                . " application.ordering AS 'ordering',"
                . " offer_type.name AS 'offertype',"
                . " programme_catalog.name AS 'name',"
                . " offer.issuedby AS 'issuedby',"
                . " offer.issuedate AS 'issuedate',"
                . " offer.revokedby AS 'revokedby',"
                . " offer.revokedate AS 'revokedate'"
                . " FROM offer"
                . " JOIN application"
                . " ON offer.applicationid = application.applicationid"
                . " JOIN academic_offering"
                . " ON application.academicofferingid = academic_offering.academicofferingid"
                . " JOIN programme_catalog"
                . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                . " JOIN offer_type"
                . " ON offer.offertypeid = offer_type.offertypeid"
                . " WHERE application.personid = " . $personid
                . " AND offer.isdeleted = 0"
                . " AND offer.ispublished = 1;"
                )
                ->queryAll();
    
        if (count($records)>0)
            return $records;
        return false;
    }
    
    
    /**
    * Returns programme details
    * 
    * @param type $offerid
    * @return string
    * 
    * Author: Laurence Charles
    * Date Created:10/01/2016
    * Date Last Modified: 10/01/2016
    */
   public static function getProgrammeDetails($offerid)
   {
        $db = Yii::$app->db;
        $record = $db->createCommand(
                    "SELECT qualification_type.abbreviation AS 'qualification',"
                    . " programme_catalog.name AS 'programmename',"
                    . " programme_catalog.specialisation AS 'specialisation'"
                    . " FROM offer"
                    . " JOIN application"
                    . " ON offer.applicationid = application.applicationid"
                    . " JOIN academic_offering"
                    . " ON application.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE offer.offerid = ". $offerid
//                    . " AND offer.isdeleted = 0;"
                    . " AND offer.isdeleted = 0;"
                    )
                    ->queryOne();
        if ($record)
            return $record;
        return false;
   }
   
   
   /**
     * Determines if a 'offer' record is associated with a CAPE programme
     * 
     * @param type $offerid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/01/2016
     * Date Last Modified: 10/01/2016
     */
    public static function isCape($offerid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                    "SELECT * "
                    . " FROM offer"
                    . " JOIN application"
                    . " ON offer.applicationid = application.applicationid"
                    . " JOIN academic_offering"
                    . " ON application.academicofferingid = academic_offering.academicofferingid"
                    . " JOIN programme_catalog"
                    . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                    . " WHERE offer.offerid = ". $offerid
                    . " AND programme_catalog.name = 'CAPE';"
                    )
                    ->queryAll();
        if (count($records) > 0)
            return true;
        return false;
    }
    
    
    /**
     * Returns the current active offer
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 28/02/2016
     * Date Last Modified: 28/02/2016
     */
    public static function getActiveOffer($personid)
    {
        $offer = Offer::find()
                ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                ->where(['offer.isdeleted' => 0, 'offer.ispublished' => 1,
                        'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9, 'application.personid' => $personid
                        ])
                ->one();
        if ($offer)
            return $offer;
        return false;
    }
    
    
    /**
     * Returns the current fulltime offer
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 28/02/2016
     * Date Last Modified: 28/02/2016
     */
    public static function getActiveFullOffer($personid)
    {
        $offer = Offer::find()
                ->innerJoin('application', '`offer`.`applicationid` = `application`.`applicationid`')
                ->where(['offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                        'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9, 'application.personid' => $personid
                        ])
                ->one();
        if ($offer)
            return $offer;
        return false;
    }
    
    
    /**
     * Returns an array of offers
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 03/03/2016
     * Date Last Modified: 03/03/2016
     */
    public static function hasRecords($personid)
    {
        $offers = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->all();
        if (count($offers) > 0)
            return true;
        return false;
    }
    
    
    /**
     * Returns the username of applicant
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 29/03/2016
     * Date Last Modified: 29/03/2016
     */
    public function getApplicantUsername()
    {
        $user = User::find()
                ->innerJoin('application' , '`application`.`personid` = `person`.`personid`')
                ->where(['person.isactive' => 1, 'person.isdeleted' => 0,
                        'application.applicationid' => $this->applicationid, 'application.isactive' => 1, 'application.isdeleted' => 0 
                        ])
                ->one();
        return $user->username;
    }
    

    /**
     * Returns the fullname of applicant
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date Last Modified: 04/11/2016
     */
    public function getApplicantFullName()
    {
        $name = "Unknown";
        
        $applicant = Applicant::find()
                ->innerJoin('application' , '`application`.`personid` = `applicant`.`personid`')
                 ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                'application.applicationid' => $this->applicationid, 'application.isactive' => 1, 'application.isdeleted' => 0
                            ])
                ->one();
        if ($applicant)
        {
            $name = $applicant->title . ". " . $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname;
        }
        return $name;
    }
    
    
    /**
     * Returns the address of applicant
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date Last Modified: 04/11/2016
     */
    public function getApplicantAddress()
    {
        $address = "Unknown";
        
        $application = Application::find()
                ->where(['applicationid' => $this->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($application)
        {
             $permanent_address = Address::find()
                    ->where(['personid' => $application->personid, 'addresstypeid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
             if ($permanent_address)
             {
                 if ($permanent_address->town == "other")
                 {
                     $address = $permanent_address->addressline . ",  " . $permanent_address->country;
                 }
                 else
                 {
                     $address = $permanent_address->town . ",  " . $permanent_address->country;
                 }
                 return $address;
             }
        }
        return $address;
    }
    
    
    /**
     * Returns the contact numbers of  applicant
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date Last Modified: 04/11/2016
     */
    public function getApplicantContact()
    {
        $contact = "Unknown";
        
        $application = Application::find()
                ->where(['applicationid' => $this->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($application)
        {
            $phone = Phone::find()
                    ->where(['personid' => $application->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            if ($phone)
            {
                $contact = $phone->homephone . ", " . $phone->workphone . ", " . $phone->cellphone;
            }
        }
        return $contact;
    }
    
    
    /**
     * Rescinds an existing offer.
     * If offer was already published, the record is made inactive;
     * If it has not been published, the record is deleted.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 01/04/2016
     * Date Last Modified: 01/04/2016
     */
    public static function rescindOffer($applicationid, $offertype)
    {
        $save_flag = false;
        
        $offer = Offer::find()
                ->where(['applicationid' => $applicationid, 'isactive' => 1, 'isdeleted' => 0, 'offertypeid' => $offertype])
                ->one();
        
        if ($offer)
        {
            if($offer->ispublished == 1)
            {
                $offer->isactive = 0;
                $offer->isdeleted = 0;
                $offer->revokedby = Yii::$app->user->getId();
                $offer->revokedate = date('Y-m-d');
            }
            else
            {
                $offer->isactive = 0;
                $offer->isdeleted = 1;
                $offer->revokedby = Yii::$app->user->getId();
                $offer->revokedate = date('Y-m-d');
            }
           
            $save_flag = $offer->save();
            if ($save_flag == true)
                return true;
            else
                return false;
        }
        else
            return false;
    }
    
    
    /**
     * Returns the type of a particular offer
     * 
     * @param type $personid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 02/04/2016
     * Date Last Modified: 02/04/2016
     */
    public static function getOfferType($personid)
    {
        $offer = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->one();
        if ($offer)
        {
            $type = OfferType::find()
                    ->where(['offertypeid' => $offer->offertypeid])
                    ->one();
            return $type->name;
        }
        return " ";
    }
    
    
    /**
     * Returns true if application has an active full offer
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 02/04/2016
     * Date Last Modified: 02/04/2016
     */
    public static function hasActiveFullOffer($personid)
    {
        $offer = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.offertypeid' => 1,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->one();
        if ($offer)
            return true;
        return false;
    }
    
    /**
     * Returns true if application has an active, published, full offer
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 02/04/2016
     * Date Last Modified: 02/04/2016
     */
    public static function hasActivePublishedFullOffer($personid)
    {
        $offer = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.offertypeid' => 1, 'offer.ispublished' => 1,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->one();
        if ($offer)
            return true;
        return false;
    }
    
    
    /**
     * Returns the type name of a particular offer
     * 
     * @param type $personid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 05/04/2016
     * Date Last Modified: 05/04/2016
     */
    public static function getPriorityOffer($personid)
    {
        $offers = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->all();
        if ($offers)
        {
            $has_full_offer = false;
            foreach($offers as $off)
            {
                if ($off->offertypeid == 1)
                    $has_full_offer = true;
            }
            
            if ($has_full_offer == true)
                return "Full Offer";
            else
                return "Interview Invitation";
        }
    }
    
    
    /**
     * Returns true if there is a pending application for the stated application period exists
     * 
     * @param type $applicationperiodid
     * @param type $offertype
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/04/2016
     * Date Last Modified: 18/04/2016
     */
    public static function offerExists($applicationperiodid, $offertype)
    {
        $offer_cond['application_period.applicationperiodid'] = $applicationperiodid;
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offertypeid'] = $offertype;
        $offer_cond['offer.isdeleted'] = 0;
        $offer_cond['offer.ispublished'] = 0;
        $offer_cond['offer.isactive'] = 1;
        
        $offers = Offer::find()
                ->innerJoin('`application`', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->groupby('offer.offerid')
                ->all();
        if($offers)
            return true;
        return false;
    }
    
    
    /**
     * Returns true if there is a pending application for the stated application-periods exists
     * 
     * @param type $applicationperiodid
     * @param type $offertype
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/04/2016
     * Date Last Modified: 18/04/2016 | 08/09/2016
     */
    public static function anyPendingOfferExists($applicationperiods, $offertype)
    {
        $periodids = array();
        foreach($applicationperiods as $period)
            $periodids[]=$period->applicationperiodid;
        
        $offer_cond['application_period.applicationperiodid'] = $periodids;
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['offer.offertypeid'] = $offertype;
        $offer_cond['offer.isdeleted'] = 0;
        $offer_cond['offer.ispublished'] = 0;
        $offer_cond['offer.isactive'] = 1;
        
        $offers = Offer::find()
                ->innerJoin('`application`', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->groupby('offer.offerid')
                ->all();
        if($offers)
            return true;
        return false;
    }
    
    
    /**
     * Returns true if there is a pending application for the stated application-periods exists
     * 
     * @param type $applicationperiodid
     * @param type $offertype
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/04/2016
     * Date Last Modified: 18/04/2016 | 08/09/2016
     */
    public static function anyOfferExists($applicationperiods, $offertype)
    {
        $periodids = array();
        foreach($applicationperiods as $period)
            $periodids[] = $period->applicationperiodid;
        
        $offer_cond['offer.offertypeid'] = $offertype;
        $offer_cond['offer.isactive'] = 1;
        $offer_cond['offer.isdeleted'] = 0;
        $offer_cond['application.isactive'] = 1;
        $offer_cond['application.isdeleted'] = 0;
        $offer_cond['academic_offering.isactive'] = 1;
        $offer_cond['academic_offering.isdeleted'] = 0;
        $offer_cond['application_period.applicationperiodid'] = $periodids;
        $offer_cond['application_period.iscomplete'] = 0;
        $offer_cond['application_period.isactive'] = 1;
        $offer_cond['application_period.isdeleted'] = 0;
        
        $offers = Offer::find()
                ->innerJoin('`application`', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($offer_cond)
                ->groupby('offer.offerid')
                ->all();
        if($offers)
            return true;
        return false;
    }
        
    
    
     /**
     * Returns offers an applicant has for a particular application period
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 15/05/2016
     * Date Last Modified: 15/05/2016
     */
    public static function hasOffer($personid, $applicationperiodid = NULL)
    {
        if ($applicationperiodid == NULL)
        {
            $offers = Offer::find()
                    ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                            'application_period.isactive' => 1, 'application_period.isdeleted' => 0 
                            ])
                    ->groupBy('application.personid')
                    ->orderBy('offer.offerid ASC')
                    ->all();
        }
        else
        {
            $offers = Offer::find()
                        ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $applicationperiodid 
                                ])
                        ->groupBy('application.personid')
                        ->orderBy('offer.offerid ASC')
                        ->all();
        }
        if ($offers)
            return $offers;
        return false;
    }
    
    
    /**
     * Returns true is pending offers exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasPendingOffers()
    {
        $offers = Offer::find()
            ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
            ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 0, 'offer.revokedby' => null,
                    'application.isactive' => 1, 'application.isdeleted' => 0,
                    'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.iscomplete' => 0
                    ])
            ->all();
        
        if ($offers)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is published offers exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasPublishedOffers()
    {
        $offers = Offer::find()
            ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
            ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.revokedby' => null,
                    'application.isactive' => 1, 'application.isdeleted' => 0,
                    'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.iscomplete' => 0
                    ])
            ->all();
        
        if ($offers)
            return true;
        return false;
    }
    
    
    /**
     * Returns true is revoked offers exist
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function hasRevokededOffers()
    {
        $offers = Offer::find()
            ->innerJoin('application' , '`application`.`applicationid` = `offer`.`applicationid`')
            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
            ->where(['offer.isdeleted' => 0,
                    'application.isactive' => 1, 'application.isdeleted' => 0,
                    'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.iscomplete' => 0
                    ])
            ->andWhere(['not', ['offer.revokedby' => null]])
            ->all();
        
        if ($offers)
            return true;
        return false;
    }
   
}
