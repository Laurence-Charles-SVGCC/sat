<?php

namespace common\models;

use Yii;
use yii\base\Model;

class StudentHoldNotificationForm extends Model
{
    public $content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["content"], "string"]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ["content" => "Content"];
    }
}
