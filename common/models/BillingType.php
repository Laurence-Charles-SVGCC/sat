<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing_type".
 *
 * @property int $id
 * @property int $billing_category_id
 * @property int $division_id
 * @property string $name
 * @property int $is_deleted
 *
 * @property BillingCharge[] $billingCharges
 * @property BillingCategory $billingCategory
 * @property Division $division
 */
class BillingType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_category_id', 'name'], 'required'],
            [['billing_category_id', 'division_id', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['billing_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillingCategory::class, 'targetAttribute' => ['billing_category_id' => 'id']],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['division_id' => 'divisionid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'billing_category_id' => 'Billing Category ID',
            'division_id' => 'Division ID',
            'name' => 'Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingCharges()
    {
        return $this->hasMany(BillingCharge::class, ['billing_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingCategory()
    {
        return $this->hasOne(BillingCategory::class, ['id' => 'billing_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['divisionid' => 'division_id']);
    }
}
