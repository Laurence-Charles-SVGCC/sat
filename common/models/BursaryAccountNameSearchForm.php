<?php

namespace common\models;

use yii\base\Model;

class BursaryAccountNameSearchForm extends Model
{
    public $first_name;
    public $last_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["first_name", "last_name"], "string"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "first_name" => "Enter first name",
            "last_name" => "Enter last name",
        ];
    }
}
