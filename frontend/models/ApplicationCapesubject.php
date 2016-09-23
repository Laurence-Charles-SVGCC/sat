<?php

namespace frontend\models;

use Yii;

use frontend\models\StudentRegistration;
use frontend\models\Application;

/**
 * This is the model class for table "application_capesubject".
 *
 * @property string $applicationcapesubjectid
 * @property string $applicationid
 * @property string $capesubjectid
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application $application
 * @property CapeSubject $capesubject
 */
class ApplicationCapesubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_capesubject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationid', 'capesubjectid'], 'required'],
            [['applicationid', 'capesubjectid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationcapesubjectid' => 'Applicationcapesubjectid',
            'applicationid' => 'Applicationid',
            'capesubjectid' => 'Capesubjectid',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }
    
    /**
     * Returns array applicationcapesubject records that belong to an applicant
     * 
     * @param type $applicationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * DAte Last Modified: 23/12/2015
     */
    public static function getRecords($applicationid)
    {
        $records = ApplicationCapesubject::find()
                ->where(['applicationid' => $applicationid, 'isdeleted' => 0])
                ->all();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    /**
     * Returns list of cape subjects associated with an application
     * 
     * @param type $studentregistrationid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 10/12/2015
     * DAte Last Modified: 23/12/2015
     */
    public static function getCapeSubjectListing($studentregistrationid)
    {
        $subjects = "";
        $registration = StudentRegistration::find()
                    ->where(['studentregistrationid' =>$studentregistrationid, 'isdeleted' => 0])
                    ->one();
        if($registration)
        {
            $application = Application::find()
                    ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                    ->where(['application.academicofferingid' => $registration->academicofferingid, 'application.personid' => $registration->personid, 'application.isdeleted' => 0,
                                    'offer.isdeleted' => 0,  'offer.isdeleted' => 0
                                ])
                    ->one();
            if($application)
            {
                $records = ApplicationCapesubject::find()
                            ->where(['applicationid' => $application->applicationid, 'isdeleted' => 0])
                            ->all();
                $count = count($records);
                
                if ($count > 0)
                {
                    for($i=0 ; $i<$count ; $i++)
                    {
                         $subject = CapeSubject::find()
                                    ->where(['capesubjectid' => $records[$i]->capesubjectid])
                                    ->one();
                        if ($i == $count-1)
                            $subjects .= $subject->subjectname;
                        else
                            $subjects .= $subject->subjectname . ",";
                    }
                }
                else
                    $subjects = "No CAPE subjects found";
            }
            else 
                $subjects = "No application found";
        }
        else 
            $subjects = "No registration found";
        return $subjects;
    }
    
    
    /**
     * Returns list of cape subjects associated with an application
     * Alternative of getCapeSubjectListing that takes advantage of studentregistration->offerid 
     * 
     * @param type $studentregistrationid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 10/01/2016
     * DAte Last Modified: 10/01/2016
     */
    public static function getCapeSubjectListing2($studentregistrationid)
    {
        $subjects = "";
        $registration = StudentRegistration::find()
                    ->where(['studentregistrationid' =>$studentregistrationid, 'isdeleted' => 0])
                    ->one();
        if($registration)
        {
            $offerid = $registration->offerid;
            if ($offerid == NULL)
                $previoussubjects = "Error retrieving subjects";
            else 
            {
                $offer = Offer::find()
                            ->where(['offerid' => $offerid])
                            ->one();
                $application = $offer->getApplication();
                if($application)
                {
                    $records = ApplicationCapesubject::find()
                                ->where(['applicationid' => $application->applicationid, 'isactive'=> 1, 'isdeleted' => 0])
                                ->all();
                    $count = count($records);

                    if ($count > 0)
                    {
                        for($i=0 ; $i<$count ; $i++)
                        {
                             $subject = CapeSubject::find()
                                        ->where(['capesubjectid' => $records[$i]->capesubjectid])
                                        ->one();
                            if ($i == $count-1)
                                $subjects .= $subject->subjectname;
                            else
                                $subjects .= $subject->subjectname . ",";
                        }
                    }
                    else
                        $previoussubjects = "Error retrieving subjects";
                }
                else
                   $previoussubjects = "Error retrieving subjects";
            }
        }
        else 
            $subjects = "No registration found";
        return $subjects;
    }
    
    
}
