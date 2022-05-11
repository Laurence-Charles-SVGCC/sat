<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cape_unit".
 *
 * @property integer $capeunitid
 * @property integer $capesubjectid
 * @property integer $academicyearid
 * @property string $unitcode
 * @property integer $coursescount
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CapeCourse[] $capeCourses
 * @property CapeSubject $capesubject
 * @property AcademicYear $academicyear
 */
class CapeUnit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capesubjectid', 'academicyearid', 'unitcode', 'coursescount'], 'required'],
            [['capesubjectid', 'academicyearid', 'coursescount', 'isactive', 'isdeleted'], 'integer'],
            [['unitcode'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capeunitid' => 'Capeunitid',
            'capesubjectid' => 'Capesubjectid',
            'academicyearid' => 'Academicyearid',
            'unitcode' => 'Unitcode',
            'coursescount' => 'Coursescount',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeCourses()
    {
        return $this->hasMany(CapeCourse::className(), ['capeunitid' => 'capeunitid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::className(), ['academicyearid' => 'academicyearid']);
    }
}
