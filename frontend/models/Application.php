<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "application".
 *
 * @property string $applicationid
 * @property string $personid
 * @property string $divisionid
 * @property string $academicofferingid
 * @property string $applicationstatusid
 * @property string $applicationtimestamp
 * @property integer $ordering
 * @property string $ipaddress
 * @property string $browseragent
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property ApplicationStatus $applicationstatus
 * @property Person $person
 * @property AcademicOffering $academicoffering
 * @property Division $division
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property ApplicationHistory[] $applicationHistories
 * @property Offer[] $offers
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
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'applicationtimestamp', 'ordering'], 'required'],
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'ordering'], 'integer'],
            [['applicationtimestamp'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['ipaddress', 'browseragent'], 'string', 'max' => 150]
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
            'divisionid' => 'Divisionid',
            'academicofferingid' => 'Academicofferingid',
            'applicationstatusid' => 'Applicationstatusid',
            'applicationtimestamp' => 'Applicationtimestamp',
            'ordering' => 'Ordering',
            'ipaddress' => 'Ipaddress',
            'browseragent' => 'Browseragent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationstatus()
    {
        return $this->hasOne(ApplicationStatus::className(), ['applicationstatusid' => 'applicationstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationHistories()
    {
        return $this->hasMany(ApplicationHistory::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['applicationid' => 'applicationid']);
    }
    
    public static function isCapeApplication($academicofferingid)
    {
        $ao = AcademicOffering::findOne(['academicofferingid' => $academicofferingid]);
        $cape_prog = ProgrammeCatalog::findOne(['name' => 'cape']);
        return $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : False;
    }
}
