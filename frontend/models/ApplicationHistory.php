<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application_history".
 *
 * @property string $applicationhistoryid
 * @property string $applicationid
 * @property string $applicationstatusid
 * @property string $entrydate
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application $application
 * @property ApplicationStatus $applicationstatus
 */
class ApplicationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationid', 'applicationstatusid', 'entrydate'], 'required'],
            [['applicationid', 'applicationstatusid'], 'integer'],
            [['entrydate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationhistoryid' => 'Applicationhistoryid',
            'applicationid' => 'Applicationid',
            'applicationstatusid' => 'Applicationstatusid',
            'entrydate' => 'Entrydate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationstatus()
    {
        return $this->hasOne(ApplicationStatus::className(), ['applicationstatusid' => 'applicationstatusid']);
    }
}
