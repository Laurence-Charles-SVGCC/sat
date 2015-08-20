<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "phone".
 *
 * @property string $phoneid
 * @property string $personid
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phoneid' => 'Phoneid',
            'personid' => 'Personid',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
