<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_status".
 *
 * @property integer $coursestatusid
 * @property string $name
 * @property string $abbreviation
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property BatchStudents[] $batchStudents
 */
class CourseStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'abbreviation'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['abbreviation'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coursestatusid' => 'Coursestatusid',
            'name' => 'Name',
            'abbreviation' => 'Abbreviation',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchStudents()
    {
        return $this->hasMany(BatchStudents::class, ['coursestatusid' => 'coursestatusid']);
    }
}
