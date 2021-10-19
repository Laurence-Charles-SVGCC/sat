<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property int $emailid
 * @property int $personid
 * @property string $email
 * @property int $priority
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property User $person
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid', 'email'], 'required'],
            [['personid', 'priority', 'isactive', 'isdeleted'], 'integer'],
            [['email'], 'string', 'max' => 45],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasOne(User::class, ['personid' => 'personid']);
    }
}
