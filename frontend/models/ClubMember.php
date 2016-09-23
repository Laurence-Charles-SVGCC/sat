<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "club_member".
 *
 * @property integer $clubmemberid
 * @property integer $clubid
 * @property integer $personid
 * @property integer $studentregistrationid
 * @property integer $clubroleid
 * @property string $appointmentdate
 * @property string $comments
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property Club $club
 * @property Person $person
 * @property ClubRole $clubrole
 */
class ClubMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clubid', 'personid', 'studentregistrationid', 'clubroleid', 'appointmentdate'], 'required'],
            [['clubid', 'personid', 'studentregistrationid', 'clubroleid', 'isactive', 'isdeleted'], 'integer'],
            [['appointmentdate'], 'safe'],
            [['comments'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clubmemberid' => 'Clubmemberid',
            'clubid' => 'Clubid',
            'personid' => 'Personid',
            'studentregistrationid' => 'Studentregistrationid',
            'clubroleid' => 'Clubroleid',
            'appointmentdate' => 'Appointmentdate',
            'comments' => 'Comments',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Club::className(), ['clubid' => 'clubid']);
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
    public function getClubrole()
    {
        return $this->hasOne(ClubRole::className(), ['clubroleid' => 'clubroleid']);
    }
    
    
    /**
     * Returns an array of clubs assignments of a particular student
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getClubs($personid)
    {
        return ClubMember::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
    }
    
    
     /**
     * Returns an array of club details a particular student is a part of
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getClubDetails($personid)
    {
        
        $collection  = array();
        
        $clubs = ClubMember::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        $keys = array();
        array_push($keys, 'recordid');
        array_push($keys, 'clubid');
        array_push($keys, 'personid');
        array_push($keys, 'clubname');
        array_push($keys, 'date');
        array_push($keys, 'comments');
        array_push($keys, 'programme');
        array_push($keys, 'role');
        
        
        foreach($clubs as $club)
        {
            $combined = array();
            $values = array();
            
            $clubname = Club::find()
                    ->where(['clubid' => $club->clubid])
                    ->one()
                    ->name;
            
            $date = $club->appointmentdate;
            $comments = $club->comments;
            
            $student_registration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $club->studentregistrationid, 'isdeleted' => 0])
                    ->one();
            $programme = ProgrammeCatalog::getProgrammeName($student_registration->academicofferingid);
            
            $role = ClubRole::find()
                    ->where(['clubroleid' => $club->clubroleid])
                    ->one()
                    ->name;
            
            array_push($values, $club->clubmemberid);
            array_push($values, $club->clubid);
            array_push($values, $club->personid);
            array_push($values, $clubname);
            array_push($values, $date);
            array_push($values, $comments);
            array_push($values, $programme);
            array_push($values, $role);
            
            $combined = array_combine($keys, $values);
            array_push($collection, $combined);
        }

        return $collection;
    }
}
