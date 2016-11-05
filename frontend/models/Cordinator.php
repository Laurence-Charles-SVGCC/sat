<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cordinator".
 *
 * @property string $cordinatorid
 * @property string $cordinatortypeid
 * @property string $personid
 * @property string $academicyearid
 * @property string $departmentid
 * @property string $academicofferingid
 * @property string $courseofferingid
 * @property string $capesubjectid
 * @property string $dateassigned
 * @property string $assignedby
 * @property string $daterevoked
 * @property string $revokeby
 * @property integer $isserving
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CordinatorType $cordinatortype
 * @property Person $person
 * @property Department $department
 * @property AcademicYear $academicyear
 * @property AcademicOffering $academicoffering
 * @property CourseOffering $courseoffering
 * @property CapeSubject $capesubject
 */
class Cordinator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cordinator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cordinatortypeid', 'personid',  'dateassigned', 'assignedby'], 'required'],
            [['cordinatortypeid', 'personid', 'academicyearid',  'departmentid', 'academicofferingid', 'courseofferingid', 'capesubjectid', 'isserving', 'isactive', 'isdeleted'], 'integer'],
            [['dateassigned', 'daterevoked'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cordinatorid' => 'Cordinatorid',
            'cordinatortypeid' => 'Cordinatortypeid',
            'personid' => 'Personid',
            'academicyearid' => 'AcademicYear ID',
            'departmentid' => 'Departmentid',
            'academicofferingid' => 'Academicofferingid',
            'courseofferingid' => 'Courseofferingid',
            'capesubjectid' => 'Capesubjectid',
            'dateassigned' => 'Dateassigned',
            'assignedby' => 'Assignedby',
            'daterevoked' => 'Daterevoked',
            'revokeby' => 'Revokeby',
            'isserving' => 'Isserving',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinatortype()
    {
        return $this->hasOne(CordinatorType::className(), ['cordinatortypeid' => 'cordinatortypeid']);
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
    public function geAcademicYear()
    {
        return $this->hasOne(AcademicYear::className(), ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['departmentid' => 'departmentid']);
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
    public function getCourseoffering()
    {
        return $this->hasOne(CourseOffering::className(), ['courseofferingid' => 'courseofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }
    
    
     /**
     * Returns the programme cordinator for a particular programme offering
     * 
     * @param type $academic_offering
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/12/2015
     * Data Last Modified: 09/12/2015
     */
    public static function getCordinator($academic_offering, $type)
    {
        $cordinator = Cordinator::find()
                    ->where(['academicofferingid' => $academic_offering, 'cordinatortypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($cordinator)
            return $cordinator;
        else
            return false;
    }
    
    
    /**
     * Returns array of cordinator types that a particular employee have been assigned.
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date Last Modified: 04/11/2016
     */
    public static function getCordinatorTypes()
    {
        $roles = Cordinator::find()
                ->where(['personid' => Yii::$app->user->getId(), 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        if ($roles)
        {
            $unique_types = array();
            foreach($roles as $role)
            {
                if(in_array($role->cordinatortypeid, $unique_types))
                {
                    continue;
                }
                else
                {
                     $unique_types[] = $role->cordinatortypeid;
                }
            }
            return $unique_types;
        }
        return false;
    }
    
    
     /**
     * Returns array of cordinated items that a particular employee have been assigned to cordinate.
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/11/2016
     * Date Last Modified: 04/11/2016
     */
    public static function getCordinationScope($cordinatortypeid)
    {
        $unique_items = array();
        if ($cordinatortypeid == 1)             //if Head of Department
        {
            
        }
        elseif($cordinatortypeid == 2)      //if Programme Head
        {
            $roles = Cordinator::find()
                ->where(['personid' => Yii::$app->user->getId(), 'cordinatortypeid' => $cordinatortypeid,  'isactive' => 1, 'isdeleted' => 0])
                ->all();
            if ($roles)
            {
                $unique_items = array();
                foreach($roles as $role)
                {
                    if(in_array($role->academicofferingid, $unique_items))
                    {
                        continue;
                    }
                    else
                    {
                         $unique_items[] = $role->academicofferingid;
                    }
                }
                return $unique_items;
            }
        }
        elseif($cordinatortypeid == 3)      //if CourseHead
        {
            
        }
        elseif($cordinatortypeid == 4)      //if CapeSubject Head
        {
            
        }
        return false;
    }
         
    
}
