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
 *
 * @property Person $person
 * @property AcademicOffering $academicoffering
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
            'personid' => 'Person',
            'academicofferingid' => 'Academicofferingid',
            'applicationdate' => 'Applicationdate',
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
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['username' => 'personid']);
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
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['applicationid' => 'applicationid']);
    }
}
