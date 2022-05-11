<?php

namespace common\models;

class SearchApplicantByEmailForm extends \yii\base\Model
{
    public $name;

    public function rules()
    {
        return [
            [["email"], "required"],
            [["email"], "email"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            "email" => "Email Address"
        ];
    }
}
