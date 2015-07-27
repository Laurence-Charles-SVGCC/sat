<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property string $offerid
 * @property string $applicationid
 * @property string $offerstatusid
 * @property string $issuedby
 * @property string $issuedate
 * @property string $revokedby
 * @property string $revokedate
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application $application
 * @property OfferStatus $offerstatus
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
            [['applicationid', 'offerstatusid', 'issuedby', 'issuedate'], 'required'],
            [['applicationid', 'offerstatusid', 'issuedby', 'revokedby'], 'integer'],
            [['issuedate', 'revokedate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean']
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
            'offerstatusid' => 'Offerstatusid',
            'issuedby' => 'Issuedby',
            'issuedate' => 'Issuedate',
            'revokedby' => 'Revokedby',
            'revokedate' => 'Revokedate',
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
    public function getOfferstatus()
    {
        return $this->hasOne(OfferStatus::className(), ['offerstatusid' => 'offerstatusid']);
    }
}
