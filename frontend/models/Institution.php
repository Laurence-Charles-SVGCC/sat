<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "institution".
 *
 * @property string $institutionid
 * @property string $levelid
 * @property string $name
 * @property string $formername
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property boolean $isactive
 * @property boolean $isdeleted
 * @property string $personid
 *
 * @property Level $level
 * @property Person $person
 * @property PersonInstitution[] $personInstitutions
 */
class Institution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'institution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'name'], 'required'],
            [['levelid', 'personid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'formername', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'institutionid' => 'Institutionid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'formername' => 'Formername',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'personid' => 'Personid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['levelid' => 'levelid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonInstitutions()
    {
        return $this->hasMany(PersonInstitution::className(), ['institutionid' => 'institutionid']);
    }
}
