<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Signup User form
 */
class PublishForm extends Model
{
    public $divisionid;
    public $statustype;
    public $test;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid', 'statustype'], 'required'],
            [['test'], 'boolean'],
        ];
    }

}
