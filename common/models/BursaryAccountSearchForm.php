<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * form used to submit account searches in bursary
 */
class BursaryAccountSearchForm extends Model
{
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["id"], "required"],
            [["id"], "string", "min" => 8],
            [["id"], "string", "max" => 10],
            ["id", "validateAccountExistence"],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ["id" => "Enter ApplicantID or StudentID"];
    }


    public function validateAccountExistence($attribute, $params)
    {
        $user = UserModel::findUserByApplicantIdOrStudentId($this->id);
        if ($user == false) {
            $this->addError($attribute, 'Account not found.');
        }
    }
}
