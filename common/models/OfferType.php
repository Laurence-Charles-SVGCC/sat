<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "offer_type".
 *
 * @property int $offertypeid
 * @property string $name
 * @property string $description
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Offer[] $offers
 */
class OfferType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'offertypeid' => 'Offertypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['offertypeid' => 'offertypeid']);
    }
}
