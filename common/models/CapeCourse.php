<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cape_course".
 *
 * @property integer $capecourseid
 * @property integer $capeunitid
 * @property integer $semesterid
 * @property string $coursecode
 * @property string $name
 * @property string $courseworkweight
 * @property string $examweight
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $createdby
 * @property integer $lastupdatedby
 *
 * @property BatchCape[] $batchCapes
 * @property CapeUnit $capeunit
 * @property Semester $semester
 * @property User $createdby0
 * @property User $lastupdatedby0
 */
class CapeCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capeunitid', 'semesterid', 'coursecode', 'name', 'courseworkweight', 'examweight'], 'required'],
            [['capeunitid', 'semesterid', 'isactive', 'isdeleted', 'createdby', 'lastupdatedby'], 'integer'],
            [['courseworkweight', 'examweight'], 'number'],
            [['coursecode'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capecourseid' => 'Capecourseid',
            'capeunitid' => 'Capeunitid',
            'semesterid' => 'Semesterid',
            'coursecode' => 'Coursecode',
            'name' => 'Name',
            'courseworkweight' => 'Courseworkweight',
            'examweight' => 'Examweight',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'createdby' => 'Createdby',
            'lastupdatedby' => 'Lastupdatedby',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchCapes()
    {
        return $this->hasMany(BatchCape::class, ['capecourseid' => 'capecourseid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeunit()
    {
        return $this->hasOne(CapeUnit::class, ['capeunitid' => 'capeunitid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::class, ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastupdatedby0()
    {
        return $this->hasOne(User::class, ['personid' => 'lastupdatedby']);
    }
}
