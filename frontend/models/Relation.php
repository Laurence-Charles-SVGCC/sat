<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "relation".
 *
 * @property string $relationid
 * @property string $relationtypeid
 * @property string $personid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $occupation
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property integer $receivemail
 * @property string $email
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property RelationType $relationtype
 * @property Person $person
 */
class Relation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relationtypeid', 'personid'], 'required'],
            [['relationtypeid', 'personid', 'receivemail', 'isactive', 'isdeleted'], 'integer'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'lastname', 'occupation', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
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
    public function getRelationtype()
    {
        return $this->hasOne(RelationType::className(), ['relationtypeid' => 'relationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
