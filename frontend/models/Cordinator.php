<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cordinator".
 *
 * @property integer $cordinatorid
 * @property integer $cordinatortypeid
 * @property integer $personid
 * @property integer $departmentid
 * @property integer $academicofferingid
 * @property integer $courseofferingid
 * @property integer $capesubjectid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $startdate
 * @property string $enddate
 * @property integer $isserving
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CordinatorType $cordinatortype
 * @property Person $person
 * @property Department $department
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
            [['cordinatortypeid', 'personid', 'title', 'firstname', 'lastname', 'startdate'], 'required'],
            [['cordinatortypeid', 'personid', 'departmentid', 'academicofferingid', 'courseofferingid', 'capesubjectid', 'isserving', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'lastname'], 'string', 'max' => 45]
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
            'departmentid' => 'Departmentid',
            'academicofferingid' => 'Academicofferingid',
            'courseofferingid' => 'Courseofferingid',
            'capesubjectid' => 'Capesubjectid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
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
}
