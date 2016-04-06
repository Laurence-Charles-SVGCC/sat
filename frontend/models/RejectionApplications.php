<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "rejection_applications".
 *
 * @property integer $rejectionid
 * @property integer $applicationid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Application $application
 * @property Rejection $rejection
 */
class RejectionApplications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rejection_applications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rejectionid', 'applicationid'], 'required'],
            [['rejectionid', 'applicationid', 'isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rejectionid' => 'Rejectionid',
            'applicationid' => 'Applicationid',
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
    public function getRejection()
    {
        return $this->hasOne(Rejection::className(), ['rejectionid' => 'rejectionid']);
    }
}
