<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "email".
 *
 * @property string $emailid
 * @property string $personid
 * @property string $email
 * @property string $priority
 * @property integer $isactive
 * @property integer $isdeleted
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
            [['personid', 'email'], 'required'],
            [['personid', 'priority', 'isactive', 'isdeleted'], 'integer'],
            [['email'], 'string', 'max' => 45]
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
            'email' => 'Email',
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
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
