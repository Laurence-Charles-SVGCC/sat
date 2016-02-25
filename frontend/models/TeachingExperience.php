<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "teaching_experience".
 *
 * @property integer $teachingexperienceid
 * @property integer $personid
 * @property string $institutionname
 * @property string $address
 * @property string $dateofappointment
 * @property string $startdate
 * @property string $enddate
 * @property string $classtaught
 * @property string $subject
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class TeachingExperience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teaching_experience';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid','institutionname', 'address', 'startdate', 'classtaught', 'subject'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['address'], 'string'],
            [['dateofappointment', 'startdate', 'enddate'], 'safe'],
            [['institutionname', 'classtaught', 'subject'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teachingexperienceid' => 'Teachingexperienceid',
            'personid' => 'Personid',
            'institutionname' => 'Institutionname',
            'address' => 'Address',
            'dateofappointment' => 'Dateofappointment',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'classtaught' => 'Classtaught',
            'subject' => 'Subject',
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
     * Retrieves all the teaching experience records associated with a personid
     * 
     * @param type $id
     * @return type
     * 
     * Date Created: 05/10/2015
     * Last Date Modified: 05/10/2015
     */
    public static function getTeachingExperiences($id)
    {
        $models = TeachingExperience::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        return $models;
    }
    
    
    /**
     * Determines if applicant has a saved 'teaching experience' record
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/10/2015
     * Last Date Modified: 31/01/2016
     */
    public static function hasExperience($id)
    {
        $models = self::getTeachingExperiences($id);
        if (count($models) > 0)
            return true;
        return false;
    }
    
    
    /**
     * Creates backup of a collection of TeachingExperience records
     * 
     * @param type $experinences
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created 20/10/2015
     * Date Last Modified: 20/10/2015
     */
    public static function backUp($experiences)
    {
        $saved = array();
         
        foreach ($experiences as $experience)
        {
            $temp = NULL;
            $temp = new TeachingExperience();
            $temp->personid = $experience->personid;
            $temp->institutionname = $experience->institutionname;
            $temp->address = $experience->address;
            $temp->dateofappointment = $experience->dateofappointment;
            $temp->startdate = $experience->startdate;
            $temp->enddate = $experience->enddate;
             $temp->classtaught = $experience->classtaught;
            $temp->subject = $experience->subject;
            array_push($saved, $temp);      
        }
        return $saved;
    }
    
    
    /**
     * Saves the backed up TeachingExperience to the databases
     * 
     * @param type $experiences
     * 
     * Date Created: 20/10/2015
     * Date Last Modified: 20/10/2015
     */
    public static function restore($experiences)
    {
        foreach ($experiences as $experience)
        {
            $experience->save();     
        }
    }
    
    
    /**
     * Determines if record is blank
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 20/10/2015
     * Date Last Modified: 20/10/2015
     */
    public function isValid()
    {
        if (
            ($this->institutionname == NULL || strcmp($this->institutionname,"")==0 || strcmp($this->institutionname,"blank")==0) 
            && ($this->address == NULL || strcmp($this->address,"")==0 || strcmp($this->address,"blank")==0) 
//            && ($this->dateofappointment == NULL || strcmp($this->dateofappointment,"")==0 || strcmp($this->dateofappointment,"2015-01-01")==0) 
            && ($this->startdate == NULL || strcmp($this->startdate,"")==0 || strcmp($this->startdate,"2015-01-01")==0) 
//            && ($this->enddate == NULL || strcmp($this->enddate,"")==0 || strcmp($this->enddate,"2015-01-01")==0) 
            && ($this->classtaught == NULL || strcmp($this->classtaught,"")==0 || strcmp($this->classtaught,"blank")==0) 
            && ($this->subject == NULL || strcmp($this->subject,"")==0 || strcmp($this->subject,"blank")==0) 
           )
                return false;
        return true;    
    }
    
}
