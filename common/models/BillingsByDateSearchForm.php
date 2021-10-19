<?php

namespace common\models;

use yii\base\Model;

class BillingsByDateSearchForm extends Model
{
    public $startDate;
    public $endDate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[["startDate", "endDate"], "safe"]];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "startDate" => "Start Date",
            "endDate" => "End Date"
        ];
    }
}
