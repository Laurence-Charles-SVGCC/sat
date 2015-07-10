<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property string $divisionid
 * @property string $locationid
 * @property string $name
 * @property string $abbreviation
 * @property string $phone
 * @property string $email
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locationid', 'name', 'abbreviation', 'phone'], 'required'],
            [['locationid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'email'], 'string', 'max' => 45],
            [['abbreviation', 'phone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'divisionid' => 'Divisionid',
            'locationid' => 'Locationid',
            'name' => 'Division Full Name',
            'abbreviation' => 'Division Abbreviated Name',
            'phone' => 'Main Phone',
            'email' => 'Main Email',
        ];
    }
}
