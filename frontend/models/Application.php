<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property string $applicationid
 * @property string $personid
 * @property string $academicofferingid
 * @property string $applicationdate
 * @property integer $ordering
 * @property string $ipaddress
 * @property string $browseragent
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'academicofferingid', 'applicationdate', 'ordering', 'ipaddress', 'browseragent'], 'required'],
            [['personid', 'academicofferingid', 'ordering'], 'integer'],
            [['applicationdate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['ipaddress', 'browseragent'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationid' => 'Applicationid',
            'personid' => 'Personid',
            'academicofferingid' => 'Academicofferingid',
            'applicationdate' => 'Applicationdate',
            'ordering' => 'Ordering',
            'ipaddress' => 'Ipaddress',
            'browseragent' => 'Browseragent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
