<?php

namespace common\models;

class SearchApplicantByApplicantIdForm extends \yii\base\Model
{
    public $applicantId;

    public function rules()
    {
        return [
            [["applicantId"], "required"],
            [["applicantId"], "string"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            "applicantId" => "Applicant ID"
        ];
    }
}
