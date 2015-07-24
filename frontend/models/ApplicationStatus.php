<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application_status".
 *
 * @property integer $applicationstatusid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application[] $applications
 */
class ApplicationStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationstatusid' => 'Applicationstatusid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['applicationstatusid' => 'applicationstatusid']);
    }
}
