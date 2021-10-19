<?php

namespace common\models;

use Yii;

class HoldTypeModel
{
    public static function getHoldTypeByID($id)
    {
        return HoldType::find()->where(["holdtypeid" => $id])->one();
    }

    public static function getHoldTypeNameByID($id)
    {
        return HoldType::find()->where(["holdtypeid" => $id])->one()->name;
    }

    public static function getAllFinancialHoldTypes()
    {
        return HoldType::find()
            ->innerJoin(
                'hold_category',
                '`hold_type`.`holdcategoryid` = `hold_category`.`holdcategoryid`'
            )
            ->where(['hold_category.name' => 'Financial'])
            ->all();
    }
}
