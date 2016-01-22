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
}
