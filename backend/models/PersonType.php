<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "person_type".
 *
 * @property string $persontypeid
 * @property string $persontype
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Person[] $people
 */
class PersonType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['persontype'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['persontype'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'persontypeid' => 'Persontypeid',
            'persontype' => 'Persontype',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['persontypeid' => 'persontypeid']);
    }
}
