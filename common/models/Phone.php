<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phone".
 *
 * @property int $phoneid
 * @property int $personid
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Person $person
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['personid' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasOne(Person::class, ['personid' => 'personid']);
    }
}
