<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property string $subjectid
 * @property string $examinationbodyid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
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
            'subjectid' => 'Subjectid',
            'examinationbodyid' => 'Examinationbodyid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
