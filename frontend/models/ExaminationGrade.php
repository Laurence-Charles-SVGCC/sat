<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "examination_grade".
 *
 * @property string $examinationgradeid
 * @property string $examinationbodyid
 * @property string $name
 * @property integer $ordering
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property CsecQualification[] $csecQualifications
 * @property ExaminationBody $examinationbody
 */
class ExaminationGrade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_grade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'name', 'ordering'], 'required'],
            [['examinationbodyid', 'ordering'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examinationgradeid' => 'Examinationgradeid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'ordering' => 'Ordering',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsecQualifications()
    {
        return $this->hasMany(CsecQualification::className(), ['examinationgradeid' => 'examinationgradeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::className(), ['examinationbodyid' => 'examinationbodyid']);
    }
}
