<?php

namespace frontend\models;

use Yii;

use frontend\models\Application;
use common\models\User;
use frontend\models\OfferType;

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
            [['issuedate', 'revokedate'], 'safe']
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
                //. " AND offer.isactive = 1"
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
                    "SELECT qualification_type.name AS 'qualification',"
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
     * Returns the current of an successful applicant
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
     * Returns the username of an offer
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
                    ->where(['offer.isactive' => 1, 'offer.isdeleted' => 0, 'offertypeid' => 1,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $personid
                            ])
                    ->one();
        if ($offer)
            return true;
        return false;
    }
    
    
    /**
     * Returns the type of a particular offer
     * 
     * @param type $personid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 05/04/2016
     * Date Last Modified: 05/04/2016
     */
    public static function getPriorityOfffer($personid)
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
            {
                $type = OfferType::find()
                        ->where(['offertypeid' => 1])
                        ->one();
            }
            else
            {
                $type = OfferType::find()
                        ->where(['offertypeid' => 2])
                        ->one();
            }
        }
        return $type->name;
    }
        
   
}
