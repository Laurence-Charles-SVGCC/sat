<?php

namespace frontend\models;

use Yii;
use frontend\models\HoldType;

/**
 * This is the model class for table "student_hold".
 *
 * @property integer $studentholdid
 * @property integer $studentregistrationid
 * @property integer $holdtypeid
 * @property integer $appliedby
 * @property integer $resolvedby
 * @property integer $holdstatus
 * @property string $details
 * @property string $dateapplied
 * @property string $dateresolved
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $wasnotified
 * @property integer $academicyearid
 *
 * @property HoldType $holdtype
 * @property Person $appliedby0
 * @property Person $resolvedby0
 * @property StudentRegistration $studentregistration
 */
class Hold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_hold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'holdtypeid', 'appliedby', 'dateapplied'], 'required'],
            [['studentregistrationid', 'holdtypeid', 'appliedby', 'resolvedby', 'holdstatus', 'isactive', 'isdeleted', 'wasnotified', 'academicyearid'], 'integer'],
            [['details'], 'string'],
            [['dateapplied', 'dateresolved'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentholdid' => 'Studentholdid',
            'studentregistrationid' => 'Studentregistrationid',
            'holdtypeid' => 'Holdtypeid',
            'appliedby' => 'Appliedby',
            'resolvedby' => 'Resolvedby',
            'holdstatus' => 'Holdstatus',
            'details' => 'Details',
            'dateapplied' => 'Dateapplied',
            'dateresolved' => 'Dateresolved',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'wasnotified' => 'Wasnotified',
            'academicyearid' => 'Academic Year',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function gethold_type()
    {
        return $this->hasOne(HoldType::className(), ['holdtypeid' => 'holdtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getAppliedby0()
    public function getAppliedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'appliedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getResolvedby0()
    public function getResolvedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'resolvedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }
    
    
    /**
     * Returns all student holds of a particular category
     * 
     * @param type $categoryid
     * @param type $studentregistrationid
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Moidified: 05/01/2015
     */
    public static function getStudentHoldByCategory($studentregistrationid, $categoryid)
    {
        $holds = Hold::find()
                ->innerJoin('hold_type', '`hold_type`.`holdtypeid` = `student_hold`.`holdtypeid`')
                ->innerJoin('hold_category', '`hold_category`.`holdcategoryid` = `hold_type`.`holdcategoryid`')
                ->where(['student_hold.studentregistrationid' => $studentregistrationid, 'hold_category.holdcategoryid' => $categoryid, 'student_hold.isactive' => 1, 'student_hold.isdeleted' => 0])
                ->all();
        
        if (count($holds)>0)
            return $holds;
        return
            false;
    }
  
    
    /**
     * Returns all student holds of a particular category
     * 
     * @param type $categoryid
     * @param type $studentregistrationid
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Moidified: 05/01/2015
     */
    public static function getStudentHoldByType($studentregistrationid, $typeid)
    {
        $holds = Hold::find()
            ->joinWith('hold_type')
            ->where(['student_hold.studentregistrationid' => $studentregistrationid, 'hold_type.holdctypeid' => $typeid, 'student_hold.isactive' => 1, 'student_hold.isdeleted' => 0])
            ->all();
        if (count($holds)>0)
            return $holds;
        return
            false;
    }
    
    
    /**
     * Returns all holds of a particular category
     * 
     * @param type $categoryid
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Moidified: 05/01/2015
     */
    public static function getHoldByCategory($categoryid)
    {
        $holds = Hold::find()
                ->innerJoin('hold_type', '`hold_type`.`holdtypeid` = `student_hold`.`holdtypeid`')
                ->innerJoin('hold_category', '`hold_category`.`holdcategoryid` = `hold_type`.`holdcategoryid`')
                ->where(['hold_category.holdcategoryid' => $categoryid, 'student_hold.isactive' => 1, 'student_hold.isdeleted' => 0])
                ->all();
        if (count($holds)>0)
            return $holds;
        return
            false;
    }
    
    /**
     * Returns all holds of a particular category
     * 
     * @param type $categoryid
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Moidified: 05/01/2016
     */
    public static function  getHoldByType($typeid)
    {
        $holds = Hold::find()
            ->joinWith('hold_type')
            ->where(['hold_type.holdtypeid' => $typeid, 'student_hold.isactive' => 1, 'student_hold.isdeleted' => 0])
            ->all();
        if (count($holds)>0)
            return $holds;
        return
            false;
    }
    
    
    /**
     * Returns the name of a particular hold
     * 
     * @param type $studentholdid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Moidified: 05/01/2016
     */
    public static function getHoldName($studentholdid)
    {
        $hold = Hold::find()
                ->where(['studentholdid' => $studentholdid, 'isactive' => 1, 'isdeleted' =>0])
                ->one();
        if($hold)
        {
            $hold_type = HoldType::find()
                    ->where(['holdtypeid' => $hold->holdtypeid, 'isactive' => 1, 'isdeleted' =>0])
                    ->one();
            if ($hold_type)
                return $hold_type->name;
            return "Error, Name could not be found.";
        }
        return "Error, Name could not be found.";
//        $hold = Hold::find()
//                ->joinWith('hold_type')
//                ->where(['student_hold.studentholdid' => $studentholdid, 'student_hold.isactive' => 1, 'student_hold.isdeleted' =>0])
//                ->one();
//        if($hold)
//           return $hold->name;
//        return false;
    }
    
    
    /**
     * Initializes array for the student holds of a particular category
     * 
     * @param type $categoryid
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 06/01/2016
     * Date Last Modified: 06/01/2016
     */
    public static function initializeHoldList($categoryid)
    {
        $holds = HoldType::find()
                ->where(['holdcategoryid' => $categoryid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();
        
        if(count($holds) <= 0)
        {
            $combined = array_combine($keys, $values);
            return $combined;   
        }
        else        //if hold records exist
        { 
            foreach($holds as $hold)
            {
                $k = strval($hold->holdtypeid);
                array_push($keys, $k);
                $v = strval($hold->name);
                array_push($values, $v);
            }  
            $combined = array_combine($keys, $values);
            return $combined;
        }
    }
    
    
}
