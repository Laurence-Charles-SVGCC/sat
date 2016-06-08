<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "course_catalog".
 *
 * @property string $coursecatalogid
 * @property string $coursecode
 * @property string $name
 * @property string $datecreated
 * @property string $datelastupdated
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CourseOffering[] $courseOfferings
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
            [['isactive', 'isdeleted'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::className(), ['coursecatalogid' => 'coursecatalogid']);
    }
}
