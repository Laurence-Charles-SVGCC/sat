<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_student".
 *
 * @property string $legacystudentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $dateofbirth
 * @property string $address
 * @property string $gender
 * @property string $legacyyearid
 * @property string $legacyfacultyid
 * @property string $createdby
 * @property string $datecreated
 * @property string $lastmodifiedby
 * @property string $datemodified
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyMarksheet[] $legacyMarksheets
 * @property LegacyYear $legacyyear
 * @property LegacyFaculty $legacyfaculty
 * @property Person $createdby0
 * @property Person $lastmodifiedby0
 */
class LegacyStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'firstname', 'middlename', 'lastname', 'gender', 'legacyyearid', 'legacyfacultyid', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'],
            [['legacyyearid', 'legacyfacultyid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['datecreated', 'datemodified', 'dateofbirth'], 'safe'],
            [['address'], 'string'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'lastname', 'gender', 'middlename'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacystudentid' => 'Legacystudentid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'legacyyearid' => 'Legacyyearid',
            'legacyfacultyid' => 'Legacyfacultyid',
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
    public function getLegacyMarksheets()
    {
        return $this->hasMany(LegacyMarksheet::className(), ['legacystudentid' => 'legacystudentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyyear()
    {
        return $this->hasOne(LegacyYear::className(), ['legacyyearid' => 'legacyyearid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyfaculty()
    {
        return $this->hasOne(LegacyFaculty::className(), ['legacyfacultyid' => 'legacyfacultyid']);
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
