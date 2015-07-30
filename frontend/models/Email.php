<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property string $emailid
 * @property string $personid
 * @property string $emailaddress
 * @property string $priority
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Person $person
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'emailaddress'], 'required'],
            [['personid', 'priority'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['emailaddress'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emailid' => 'Emailid',
            'personid' => 'Personid',
            'emailaddress' => 'Emailaddress',
            'priority' => 'Priority',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }
}
