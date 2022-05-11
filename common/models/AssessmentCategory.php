<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_category".
 *
 * @property integer $assessmentcategoryid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property AssessmentType[] $assessmentTypes
 */
class AssessmentCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessmentcategoryid' => 'Assessmentcategoryid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentTypes()
    {
        return $this->hasMany(AssessmentType::class, ['assessmentcategoryid' => 'assessmentcategoryid']);
    }
}
