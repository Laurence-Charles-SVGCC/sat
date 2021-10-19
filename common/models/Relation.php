<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "relation".
 *
 * @property int $relationid
 * @property int $relationtypeid
 * @property int $personid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $occupation
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property int $receivemail
 * @property string $email
 * @property string $address
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property CompulsoryRelation[] $compulsoryRelations
 * @property RelationType $relationtype
 * @property Person $person
 */
class Relation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['relationtypeid', 'personid', 'title', 'firstname', 'lastname', 'address'], 'required'],
            [['relationtypeid', 'personid', 'receivemail', 'isactive', 'isdeleted'], 'integer'],
            [['address'], 'string'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'lastname', 'occupation', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15],
            [['relationtypeid'], 'exist', 'skipOnError' => true, 'targetClass' => RelationType::class, 'targetAttribute' => ['relationtypeid' => 'relationtypeid']],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['personid' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'relationid' => 'Relationid',
            'relationtypeid' => 'Relationtypeid',
            'personid' => 'Personid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'occupation' => 'Occupation',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'receivemail' => 'Receivemail',
            'email' => 'Email',
            'address' => 'Address',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompulsoryRelations()
    {
        return $this->hasMany(CompulsoryRelation::class, ['relationtypeid' => 'relationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationtype()
    {
        return $this->hasOne(RelationType::class, ['relationtypeid' => 'relationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['personid' => 'personid']);
    }
}
