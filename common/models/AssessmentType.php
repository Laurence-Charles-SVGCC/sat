<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assessment_type".
 *
 * @property integer $assessmenttypeid
 * @property integer $assessmentcategoryid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Assessment[] $assessments
 * @property AssessmentCape[] $assessmentCapes
 * @property AssessmentCategory $assessmentcategory
 */
class AssessmentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assessmentcategoryid', 'name'], 'required'],
            [['assessmentcategoryid', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assessmenttypeid' => 'Assessmenttypeid',
            'assessmentcategoryid' => 'Assessmentcategoryid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessments()
    {
        return $this->hasMany(Assessment::class, ['assessmenttypeid' => 'assessmenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentCapes()
    {
        return $this->hasMany(AssessmentCape::class, ['assessmenttypeid' => 'assessmenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessmentcategory()
    {
        return $this->hasOne(AssessmentCategory::class, ['assessmentcategoryid' => 'assessmentcategoryid']);
    }
}
