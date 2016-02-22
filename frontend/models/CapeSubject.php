<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cape_subject".
 *
 * @property string $capesubjectid
 * @property string $cordinatorid
 * @property string $academicofferingid
 * @property string $subjectname
 * @property integer $unitcount
 * @property integer $capacity
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property Person $cordinator
 * @property AcademicOffering $academicoffering
 * @property CapeSubjectGroup[] $capeSubjectGroups
 * @property CapeGroup[] $capegroups
 * @property CapeUnit[] $capeUnits
 */
class CapeSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academicofferingid', 'subjectname'], 'required'],
            [['cordinatorid', 'academicofferingid', 'unitcount', 'capacity'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['subjectname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capesubjectid' => 'Capesubjectid',
            'cordinatorid' => 'Cordinatorid',
            'academicofferingid' => 'Academicofferingid',
            'subjectname' => 'Subjectname',
            'unitcount' => 'Unitcount',
            'capacity' => 'Capacity',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::className(), ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinator()
    {
        return $this->hasOne(Person::className(), ['personid' => 'cordinatorid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeSubjectGroups()
    {
        return $this->hasMany(CapeSubjectGroup::className(), ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapegroups()
    {
        return $this->hasMany(CapeGroup::className(), ['capegroupid' => 'capegroupid'])->viaTable('cape_subject_group', ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeUnits()
    {
        return $this->hasMany(CapeUnit::className(), ['capesubjectid' => 'capesubjectid']);
    }
    
    
    /**
     *Returns key=>value array of capesubjectid=>subjectname
     *  
     * @param type $subjects
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 09/01/2016
     */
    public static function processGroup($subjects)
    {
        $combined = array();
        $keys = array();
        $values = array();
        array_push($keys, '0');
        array_push($values, 'None');
        foreach($subjects as $subject)
        {
            $target = CapeSubject::find()
                    ->where(['capesubjectid' => $subject->capesubjectid])
                    ->one();
            $k = strval($target->capesubjectid);
            $v = strval($target->subjectname);
            array_push($keys, $k);
            array_push($values, $v);
        }
        $combined = array_combine($keys, $values);
        return $combined;
    }
    
    
    /**
    * Determines of a CAPE offering has been created for a particular appolication period
    * 
    * @param type $applicationperiodid
    * @return boolean
    * 
    * Author: Laurence Charles
    * Date Created: 14/02/2016
    * Date Last Modified: 14/02/2016
    */
//   public static function getCapeSubjects($applicationperiodid)
//   {
//        $db = Yii::$app->db;
//        $records = $db->createCommand(
//                "SELECT *"
//                . " FROM cape_subject" 
//                . " JOIN academic_offering"
//                . " ON cape_subject.academicofferingid = academic_offering.academicofferingid"
//                . " WHERE academic_offering.applicationperiodid =" . $applicationperiodid
//                . " AND academic_offering.isactive = 1"
//                . " AND academic_offering.isactive = 0"
//                . ";"
//            )
//            ->queryAll();
//        if (count($records) > 0)
//            return $records;
//        return false;
//    }
    
    
    /**
    * Retrives all cape_subject records related to the given CAPE academic offering
    * 
    * @param type $academicofferingid
    * @return boolean
    * 
    * Author: Laurence Charles
    * Date Created: 15/02/2016
    * Date Last Modified: 15/02/2016
    */
   public static function getCapeSubjects($academicofferingid)
   {
       $records = CapeSubject::find()
               ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid`=`academic_offering`.`academicofferingid`')
               ->where(['cape_subject.academicofferingid' => $academicofferingid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
               ->all();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    /**
     * Creates backup of a collection of CapeSubjects records
     * 
     * @param type $subjects
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function backUp($subjects)
    {
        $saved = array();
         
        foreach ($subjects as $subject)
        {
            $temp = NULL;
            $temp = new CapeSubject();
            $temp->cordinatorid = $subject->cordinatorid;
            $temp->academicofferingid = $subject->academicofferingid;
            $temp->subjectname = $subject->subjectname;
            $temp->unitcount = $subject->unitcount;
            $temp->capacity = $subject->capacity;
            $temp->isactive = $subject->isactive;
            $temp->isdeleted = $subject->isdeleted;
            array_push($saved, $temp);      
        }
        return $saved;
    }
    
    
    /**
     * Restores the backed up CapeSubjects to the database
     * 
     * @param type $subjects
     * 
     * Author: Laurence Charles
     * Date Created: 15/02/2016
     * Date Last Modified: 15/02/2016
     */
    public static function restore($subjects)
    {
        foreach ($subjects as $subject)
        {
            $subject->save();     
        }
    }
    
    
    
}
