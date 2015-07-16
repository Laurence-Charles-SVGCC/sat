<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "examination_body".
 *
 * @property string $examinationbodyid
 * @property string $levelid
 * @property string $name
 * @property string $alias
 * @property string $abbreviation
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class ExaminationBody extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_body';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'name', 'alias', 'abbreviation'], 'required'],
            [['levelid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'alias'], 'string', 'max' => 45],
            [['abbreviation'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examinationbodyid' => 'Examinationbodyid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'alias' => 'Alias',
            'abbreviation' => 'Abbreviation',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
