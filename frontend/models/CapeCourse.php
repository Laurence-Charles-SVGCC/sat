<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cape_course".
 *
 * @property string $capecourseid
 * @property string $capeunitid
 * @property string $semesterid
 * @property string $coursecode
 * @property string $name
 * @property string $courseworkweight
 * @property string $examweight
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property BatchCape[] $batchCapes
 * @property CapeUnit $capeunit
 * @property Semester $semester
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
            [['capeunitid', 'semesterid', 'isactive', 'isdeleted'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchCapes()
    {
        return $this->hasMany(BatchCape::className(), ['capecourseid' => 'capecourseid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeunit()
    {
        return $this->hasOne(CapeUnit::className(), ['capeunitid' => 'capeunitid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semesterid' => 'semesterid']);
    }
}
