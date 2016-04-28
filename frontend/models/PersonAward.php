<?php

namespace frontend\models;

use Yii;
use frontend\models\Award;

/**
 * This is the model class for table "person_award".
 *
 * @property integer $personawardid
 * @property integer $personid
 * @property integer $awardid
 * @property integer $studentregistrationid
 * @property string $dateawarded
 * @property string $comments
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property Award $award
 * @property StudentRegistration $studentregistration
 */
class PersonAward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'awardid', 'studentregistrationid', 'dateawarded'], 'required'],
            [['personid', 'awardid', 'studentregistrationid', 'isactive', 'isdeleted'], 'integer'],
            [['dateawarded'], 'safe'],
            [['comments'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personawardid' => 'Personawardid',
            'personid' => 'Personid',
            'awardid' => 'Awardid',
            'studentregistrationid' => 'Studentregistrationid',
            'dateawarded' => 'Dateawarded',
            'comments' => 'Comments',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAward()
    {
        return $this->hasOne(Award::className(), ['awardid' => 'awardid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }
    
    
    /**
     * Returns an array of awards assigned to a particular student
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getAwards($personid)
    {
        return PersonAward::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
    }
    
    
     /**
     * Returns an array of award details assigned to a particular student
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getAwardDetails($personid)
    {
        
        $collection  = array();
        
        $awards = PersonAward::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        $keys = array();
        array_push($keys, 'recordid');
        array_push($keys, 'awardid');
        array_push($keys, 'personid');
        array_push($keys, 'awardname');
        array_push($keys, 'date');
        array_push($keys, 'comments');
        array_push($keys, 'programme');

        
       
        foreach($awards as $award)
        {
            $combined = array();
            $values = array();
            
            $awardname = Award::find()
                    ->where(['awardid' => $award->awardid])
                    ->one()->name;
            
            $date = $award->dateawarded;
            $comments = $award->comments;
            
            $student_registration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $award->studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $programme = ProgrammeCatalog::getProgrammeName($student_registration->academicofferingid);
            
            array_push($values, $award->personawardid);
            array_push($values, $award->awardid);
            array_push($values, $award->personid);
            array_push($values, $awardname);
            array_push($values, $date);
            array_push($values, $comments);
            array_push($values, $programme);
            
            $combined = array_combine($keys, $values);
            array_push($collection, $combined);
        }

        return $collection;
    }
}
