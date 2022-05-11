<?php

namespace common\models;

class SearchApplicantByApplicantIdForm extends \yii\base\Model
{
    public $firstName;
    public $lastName;

    public function rules()
    {
        return [
            [["firstName", "lastName"], "string"]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            "firstName" => "First Name",
            "lastName" => "Last Name"
        ];
    }
}
