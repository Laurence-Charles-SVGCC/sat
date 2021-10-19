<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing_category".
 *
 * @property int $id
 * @property int $billing_scope_id
 * @property string $name
 * @property int $is_deleted
 *
 * @property BillingScope $billingScope
 * @property BillingType[] $billingTypes
 */
class BillingCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_scope_id', 'name'], 'required'],
            [['billing_scope_id', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['billing_scope_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillingScope::class, 'targetAttribute' => ['billing_scope_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'billing_scope_id' => 'Billing Scope ID',
            'name' => 'Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingScope()
    {
        return $this->hasOne(BillingScope::class, ['id' => 'billing_scope_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingTypes()
    {
        return $this->hasMany(BillingType::class, ['billing_category_id' => 'id']);
    }
}
