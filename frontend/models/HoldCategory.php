<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "hold_category".
 *
 * @property integer $holdcategoryid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property HoldType[] $holdTypes
 */
class HoldCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hold_category';
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
            'holdcategoryid' => 'Holdcategoryid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHoldTypes()
    {
        return $this->hasMany(HoldType::className(), ['holdcategoryid' => 'holdcategoryid']);
    }
}
