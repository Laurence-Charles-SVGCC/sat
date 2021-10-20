<?php

namespace common\models;

use Yii;
use yii\base\Model;

class StudentHoldForm extends Model
{
    public $studentregistrationid;
    public $holdtypeid;
    public $details;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["studentregistrationid", "holdtypeid"], "required"],
            [["studentregistrationid", "holdtypeid"], "integer"],
            [["details"], "string"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "studentregistrationid" => "Select Enrollment",
            "details" => "Notes"
        ];
    }


    public function generateFinancialHold($userID)
    {
        $hold = new StudentHold();
        $hold->holdtypeid = $this->holdtypeid;
        $hold->studentregistrationid = $this->studentregistrationid;
        $hold->details = $this->details;
        $hold->appliedby = $userID;
        $hold->dateapplied = date("Y-m-d");
        return $hold;
    }
}
