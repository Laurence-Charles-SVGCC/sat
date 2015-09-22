<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address_type".
 *
 * @property string $addresstypeid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Address[] $addresses
 * @property Location[] $locations
 */
class AddressType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'addresstypeid' => 'Addresstypeid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['addresstypeid' => 'addresstypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['addresstypeid' => 'addresstypeid']);
    }
}
