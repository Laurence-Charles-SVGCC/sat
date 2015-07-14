<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "contact_info".
 *
 * @property string $contactinfoid
 * @property string $locationid
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property string $email
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class ContactInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locationid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contactinfoid' => 'Contactinfoid',
            'locationid' => 'Locationid',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'email' => 'Email',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
