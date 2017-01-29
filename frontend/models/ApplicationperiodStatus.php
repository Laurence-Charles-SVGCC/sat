<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "applicationperiod_status".
 *
 * @property string $applicationperiodstatusid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ApplicationPeriod[] $applicationPeriods
 */
class ApplicationperiodStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicationperiod_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string'],
            [['isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationperiodstatusid' => 'Applicationperiodstatusid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::className(), ['applicationperiodstatusid' => 'applicationperiodstatusid']);
    }
}
