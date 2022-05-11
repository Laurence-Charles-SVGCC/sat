<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "semester".
 *
 * @property integer $semesterid
 * @property integer $academicyearid
 * @property string $title
 * @property string $period
 * @property string $startdate
 * @property string $enddate
 * @property integer $iscurrent
 * @property integer $isactive
 * @property integer $isdeleted
 * @property string $publishgradesdate
 *
 * @property Award[] $awards
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
            [['academicyearid', 'title', 'period', 'startdate', 'enddate'], 'required'],
            [['academicyearid', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate', 'publishgradesdate'], 'safe'],
            [['title'], 'string', 'max' => 3],
            [['period'], 'string', 'max' => 45]
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
            'period' => 'Period',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'iscurrent' => 'Iscurrent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'publishgradesdate' => 'Publishgradesdate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::class, ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeCourses()
    {
        return $this->hasMany(CapeCourse::class, ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::class, ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(AcademicYear::class, ['academicyearid' => 'academicyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['semesterid' => 'semesterid']);
    }
}
