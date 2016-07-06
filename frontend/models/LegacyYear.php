<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_year".
 *
 * @property string $legacyyearid
 * @property string $name
 * @property string $createdby
 * @property string $datecreated
 * @property string $lastmodifiedby
 * @property string $datemodified
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyStudent[] $legacyStudents
 * @property Person $createdby0
 * @property Person $lastmodifiedby0
 */
class LegacyYear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_year';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'],
            [['createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['datecreated', 'datemodified'], 'safe'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacyyearid' => 'Legacyyearid',
            'name' => 'Name',
            'createdby' => 'Createdby',
            'datecreated' => 'Datecreated',
            'lastmodifiedby' => 'Lastmodifiedby',
            'datemodified' => 'Datemodified',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyStudents()
    {
        return $this->hasMany(LegacyStudent::className(), ['legacyyearid' => 'legacyyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lastmodifiedby']);
    }
}
