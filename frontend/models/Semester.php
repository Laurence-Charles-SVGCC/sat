<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "semester".
 *
 * @property string $semesterid
 * @property string $academicyearid
 * @property string $title
 * @property string $startdate
 * @property string $enddate
 * @property boolean $iscurrent
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property CapeCourse[] $capeCourses
 * @property CourseOffering[] $courseOfferings
 * @property AcademicYear $academicyear
 * @property Transaction[] $transactions
 */
class Semester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'semester';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academicyearid', 'title', 'startdate', 'enddate'], 'required'],
            [['academicyearid'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['iscurrent', 'isactive', 'isdeleted'], 'boolean'],
            [['title'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'semesterid' => 'Semesterid',
            'academicyearid' => 'Academicyearid',
            'title' => 'Title',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'iscurrent' => 'Iscurrent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeCourses()
    {
        return $this->hasMany(CapeCourse::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::className(), ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['semesterid' => 'semesterid']);
    }
}
