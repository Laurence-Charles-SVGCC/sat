<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "examination_proficiency_type".
 *
 * @property string $examinationproficiencytypeid
 * @property string $examinationbodyid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class ExaminationProficiencyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_proficiency_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'name'], 'required'],
            [['examinationbodyid'], 'integer'],
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
            'examinationproficiencytypeid' => 'Examinationproficiencytypeid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
