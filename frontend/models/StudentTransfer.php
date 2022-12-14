<?php

namespace frontend\models;

use Yii;
use frontend\models\Employee;
use frontend\models\StudentRegistration;
use frontend\models\Offer;
use frontend\models\ApplicationCapesubject;
use frontend\models\CapeSubject;
use frontend\models\ProgrammeCatalog;

/**
 * This is the model class for table "student_transfer".
 *
 * @property integer $studenttransferid
 * @property integer $studentregistrationid
 * @property integer $personid
 * @property integer $transferofficer
 * @property integer $offerfrom
 * @property integer $offerto
 * @property integer $transferdate
 * @property string $details
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $offerfrom
 * @property StudentRegistration $offerto
 */
class StudentTransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'personid', 'transferofficer', 'offerfrom', 'offerto', 'transferdate'], 'required'],
            [['transferdate'], 'safe'],
            [['studentregistrationid', 'personid', 'transferofficer', 'offerfrom', 'offerto', 'isactive', 'isdeleted'], 'integer'],
            [['details'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studenttransferid' => 'Studenttransferid',
            'studentregistrationid' => 'Studentregistrationid',
            'transferofficer' => 'Transfer Officer',
            'personid' => 'Personid',
            'offerfrom' => 'Old Offer',
            'offerto' => 'New Offer',
            'transferdate' => 'Ttransfer Date',
            'details' => 'Details',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferfrom()
    {
        return $this->hasOne(Offer::className(), ['offerid' => 'offerfrom']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferto()
    {
        return $this->hasOne(Offer::className(), ['offerid' => 'offerto']);
    }
    
    
    //Inaccurate implementation
    public static function prepareTransfers($studentregistrationid)
    {   
        $container = array();
        $end = false;
        
        $transfer = StudentTransfer::find()
                    ->where(['studentregistrationto' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0 ])
                    ->one();
        while ($transfer == true)
        {
            $keys = array();
            $values = array();
            $combined = array();
            
            array_push($keys, 'transferdate');
            array_push($keys, 'previousprogramme');
            array_push($keys, 'previoussubjects');
            array_push($keys, 'newprogramme');
            array_push($keys, 'newsubjects');
            array_push($keys, 'transferofficer');
            array_push($keys, 'details');
            
            $transferdate = $transfer->transferdate;
            
            $old_programme = StudentRegistration::getProgrammeDetails($transfer->studentregistrationfrom);
            $previousprogramme = $programme["qualification"] . " " . $programme["programmename"] . " " . $programme["specialisation"]; 
            
            $previoussubjects = "";
            if (StudentRegistration::isCape($tranfer->studentregistrationfrom) == false)
                $previoussubjects = "N/A";
            else 
            {
                $registration = StudentRegistration::find()
                        ->where(['studentregistrationid' => $transfer->studentregistrationfrom, 'isdeleted' => 0 ])
                        ->one();
                $offerid = $registration->offerid;
                if ($offerid == NULL)
                    $previoussubjects = "Error retrieving subjects";
                else 
                {
                    $offer = Offer::find()
                                ->where(['offerid' => $offerid])
                                ->one();
                    $application = Application::find()
                                ->where(['applicationid' => $offer->applicationid])
                                ->one();
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
                                    $previoussubjects .= $subject->subjectname;
                                else
                                    $previoussubjects .= $subject->subjectname . ",";
                            }
                        }
                        else
                            $previoussubjects = "Error retrieving subjects";
                    }
                    else
                       $previoussubjects = "Error retrieving subjects";
                }
            }
            
            $new_programme = StudentRegistration::getProgrammeDetails($transfer->studentregistrationto);
            $newprogramme = $programme["qualification"] . " " . $programme["programmename"] . " " . $programme["specialisation"];         
                    
            $newsubjects = "";
            if (StudentRegistration::isCape($transfer->studentregistrationto) == false)
                $newsubjects = "N/A";
            else 
            {
                $registration = StudentRegistration::find()
                        ->where(['studentregistrationid' => $transfer->studentregistrationto, 'isdeleted' => 0 ])
                        ->one();
                $offerid = $registration->offerid;
                if ($offerid == NULL)
                    $newsubjects = "Error retrieving subjects";
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
                                    $newsubjects .= $subject->subjectname;
                                else
                                    $newsubjects .= $subject->subjectname . ",";
                            }
                        }
                        else
                            $newsubjects = "Error retrieving subjects";
                    }
                    else
                       $newsubjects = "Error retrieving subjects";
                }
            }
            
            $transferofficer = Employee::getEmployeeName($transfer->personid);
            
            if ($transfer->details == NULL || strcmp($transfer->details,"") == 0)
                    $details = "N/A";
            else
                $details = $transfer->details;
            
            array_push($values, $transferdate);
            array_push($values, $previousprogramme);
            array_push($values, $previoussubjects);
            array_push($values, $newprogramme);
            array_push($values, $newsubjects);
            array_push($values, $transferofficer);
            array_push($values, $details);
            
            $combined = array_combine($keys, $values);
            array_push($container, $combined);

            $subjects = NULL;
            $keys = NULL;
            $values = NULL;
            $combined = NULL;
            $transfer = StudentTransfer::find()
                ->where(['studentregistrationto' => $transfer->studentregistrationfrom, 'isactive' => 1, 'isdeleted' => 0 ])
                ->one();
            
        }
        
        if (count($container) > 0)
            return $container;  
        return false;
    }
    
    
    /**
     * Get all the transfer records for a particular studentregistration
     * 
     * @param type $studentregistrationid
     * @return boolean|array
     * 
     * Author: Laurence Charles
     * Date Created: 10/01/2016
     * Date Last Modified: 10/01/2016
     */
    public static function getTransfers($personid)
    {
        $container = array();
        $end = false;
        
        $transfers = StudentTransfer::find()
//                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0 ])
                    ->where(['personid' => $personid, 'isdeleted' => 0 ])
                    ->all();
        if ($transfers)
        {
            foreach($transfers as $transfer)
            {
                $keys = array();
                $values = array();
                $combined = array();
                
                array_push($keys, 'transferdate');
                array_push($keys, 'previousprogramme');
                array_push($keys, 'newprogramme');
                array_push($keys, 'transferofficer');
                array_push($keys, 'details');

                $transferdate = $transfer->transferdate;
                
                $offer_from = Offer::find()
                        ->where(['offerid' => $transfer->offerfrom, 'isdeleted' => 0])
                        ->one();
                if($offer_from == false)
                    continue;
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = $offer_from->getApplication()->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid, 'isdeleted' => 0]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $previous_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $previousprogramme = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $previous_cape_subjects_names);
                
                
                $offer_to = Offer::find()
                        ->where(['offerid' => $transfer->offerto, 'isdeleted' => 0])
                        ->one();
                if($offer_to == false)
                    continue;
                $transfer_info["offer_to_id"] = $offer_to->offerid;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = $offer_to->getApplication()->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid, 'isdeleted' => 0]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $newprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                
                $transferofficer = Employee::getEmployeeName($transfer->transferofficer);

                if ($transfer->details == NULL || strcmp($transfer->details,"") == 0)
                        $details = "N/A";
                else
                    $details = $transfer->details;

                array_push($values, $transferdate);
                array_push($values, $previousprogramme);
                array_push($values, $newprogramme);
                array_push($values, $transferofficer);
                array_push($values, $details);

                $combined = array_combine($keys, $values);
                array_push($container, $combined);

                $subjects = NULL;
                $keys = NULL;
                $values = NULL;
                $combined = NULL;
            }
        }
        
        if (count($container) > 0)
            return $container;  
        return false;
    }
    
    
}
