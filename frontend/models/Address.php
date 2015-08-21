<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "address".
 *
 * @property string $addressid
 * @property string $personid
 * @property string $addresstypeid
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property AddressType $addresstype
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'addresstypeid'], 'required'],
            [['personid', 'addresstypeid', 'isactive', 'isdeleted'], 'integer'],
            [['country', 'constituency', 'town'], 'string', 'max' => 45],
            [['addressline'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'addressid' => 'Addressid',
            'personid' => 'Personid',
            'addresstypeid' => 'Addresstypeid',
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
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresstype()
    {
        return $this->hasOne(AddressType::className(), ['addresstypeid' => 'addresstypeid']);
    }
}
