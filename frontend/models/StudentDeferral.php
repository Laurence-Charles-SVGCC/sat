<?php

namespace frontend\models;

use Yii;

use frontend\models\AcademicYear;

/**
 * This is the model class for table "student_deferral".
 *
 * @property string $studentdeferralid
 * @property string $personid
 * @property string $deferralofficer
 * @property string $registrationfrom
 * @property string $registrationto
 * @property string $deferraldate
 * @property string $details
 * @property integer $isactive
 * @property integer $isdeleted
 */
class StudentDeferral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_deferral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'deferralofficer', 'registrationfrom', 'registrationto', 'deferraldate'], 'required'],
            [['personid', 'deferralofficer', 'registrationfrom', 'registrationto', 'isactive', 'isdeleted'], 'integer'],
            [['deferraldate'], 'safe'],
            [['details'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentdeferralid' => 'Studentdeferralid',
            'personid' => 'Personid',
            'deferralofficer' => 'Deferralofficer',
            'registrationfrom' => 'Registrationfrom',
            'registrationto' => 'Registrationto',
            'deferraldate' => 'Deferraldate',
            'details' => 'Details',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    public static function getDeferrals($personid)
    {
        $container = array();
        $end = false;
        
        $deferrals = StudentDeferral::find()
                    ->where(['personid' => $personid, 'isdeleted' => 0 ])
                    ->all();
        
        if ($deferrals)
        {
            foreach($deferrals as $deferral)
            {
                $keys = array();
                $values = array();
                $combined = array();
                
                array_push($keys, 'deferraldate');
                array_push($keys, 'previousprogramme');
                array_push($keys, 'newprogramme');
                array_push($keys, 'deferralofficer');
                array_push($keys, 'details');

                $deferraldate = $deferral->deferraldate;
                
                $registration_from = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationfrom, 'isdeleted' => 0])
                        ->one();
                $offer_from = Offer::find()
                        ->where(['offerid' => $registration_from->offerid, 'isdeleted' => 0])
                        ->one();
                if($offer_from == false)
                    continue;
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = $offer_from->getApplication()->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $previous_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $previousprogramme = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $previous_cape_subjects_names);
                $previous_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $previous_application->academicofferingid, 'academic_offering.isdeleted' => 0
                                    ])
                        ->one();
                $previous_programme= "(" . $previous_year->title . ") " .  $previousprogramme;
                
                $registration_to = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationto, 'isdeleted' => 0])
                        ->one();
                $offer_to = Offer::find()
                        ->where(['offerid' => $registration_to->offerid, 'isdeleted' => 0])
                        ->one();
                if($offer_to == false)
                    continue;
                $transfer_info["offer_to_id"] = $offer_to->offerid;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = $offer_to->getApplication()->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $newprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                 $current_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $current_application->academicofferingid, 'academic_offering.isdeleted' => 0])
                        ->one();
                $new_programme = "(" . $current_year->title . ") " .  $newprogramme;
                
                
                $deferralofficer = Employee::getEmployeeName($deferral->deferralofficer);

                if ($deferral->details == NULL || strcmp($deferral->details,"") == 0)
                        $details = "N/A";
                else
                    $details = $deferral->details;

                array_push($values, $deferraldate);
                array_push($values, $previous_programme);
                array_push($values, $new_programme);
                array_push($values, $deferralofficer);
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
