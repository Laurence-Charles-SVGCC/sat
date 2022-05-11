<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_catalog".
 *
 * @property integer $coursecatalogid
 * @property string $coursecode
 * @property string $name
 * @property string $datecreated
 * @property string $datelastupdated
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $coursetypeid
 * @property integer $passfailtypeid
 * @property integer $credits
 *
 * @property CourseOffering[] $courseOfferings
 * @property GraduationProgrammeCourse[] $graduationProgrammeCourses
 */
class CourseCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coursecode', 'name', 'datecreated', 'datelastupdated'], 'required'],
            [['datecreated', 'datelastupdated'], 'safe'],
            [['isactive', 'isdeleted', 'coursetypeid', 'passfailtypeid', 'credits'], 'integer'],
            [['coursecode'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coursecatalogid' => 'Coursecatalogid',
            'coursecode' => 'Coursecode',
            'name' => 'Name',
            'datecreated' => 'Datecreated',
            'datelastupdated' => 'Datelastupdated',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'coursetypeid' => 'Coursetypeid',
            'passfailtypeid' => 'Passfailtypeid',
            'credits' => 'Credits',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::class, ['coursecatalogid' => 'coursecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraduationProgrammeCourses()
    {
        return $this->hasMany(GraduationProgrammeCourse::class, ['coursecatalogid' => 'coursecatalogid']);
    }
}
