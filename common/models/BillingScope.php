<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "billing_scope".
 *
 * @property int $id
 * @property string $name
 * @property int $is_deleted
 *
 * @property BillingCategory[] $billingCategories
 */
class BillingScope extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing_scope';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillingCategories()
    {
        return $this->hasMany(BillingCategory::class, ['billing_scope_id' => 'id']);
    }
}
