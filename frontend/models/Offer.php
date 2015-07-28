<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property string $offerid
 * @property string $applicationid
 * @property string $issuedby
 * @property string $issuedate
 * @property string $revokedby
 * @property string $revokedate
 * @property boolean $ispublished
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application $application
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationid', 'issuedby', 'issuedate'], 'required'],
            [['applicationid', 'issuedby', 'revokedby'], 'integer'],
            [['issuedate', 'revokedate'], 'safe'],
            [['ispublished', 'isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerid' => 'Offerid',
            'applicationid' => 'Applicationid',
            'issuedby' => 'Issuedby',
            'issuedate' => 'Issuedate',
            'revokedby' => 'Revokedby',
            'revokedate' => 'Revokedate',
            'ispublished' => 'Ispublished',
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
}
