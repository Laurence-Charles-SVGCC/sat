<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "graduation_programme_course".
 *
 * @property string $graduationprogrammecourseid
 * @property string $programmecatalogid
 * @property string $coursecatalogid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ProgrammeCatalog $programmecatalog
 * @property CourseCatalog $coursecatalog
 */
class GraduationProgrammeCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'graduation_programme_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programmecatalogid', 'coursecatalogid'], 'required'],
            [['programmecatalogid', 'coursecatalogid', 'isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'graduationprogrammecourseid' => 'Graduationprogrammecourseid',
            'programmecatalogid' => 'Programmecatalogid',
            'coursecatalogid' => 'Coursecatalogid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammecatalog()
    {
        return $this->hasOne(ProgrammeCatalog::className(), ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursecatalog()
    {
        return $this->hasOne(CourseCatalog::className(), ['coursecatalogid' => 'coursecatalogid']);
    }
}
