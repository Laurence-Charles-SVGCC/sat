<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_batch".
 *
 * @property string $legacybatchid
 * @property string $legacytermid
 * @property string $legacysubjectid
 * @property string $legacybatchtypeid
 * @property string $legacylevelid
 * @property string $name
 * @property string $createdby
 * @property string $datecreated
 * @property string $lastmodifiedby
 * @property string $datemodified
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyTerm $legacyterm
 * @property LegacySubject $legacysubject
 * @property LegacyBatchType $legacybatchtype
 * @property LegacyLevel $legacylevel
 * @property Person $createdby0
 * @property Person $lastmodifiedby0
 * @property LegacyMarksheet[] $legacyMarksheets
 */
class LegacyBatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legacytermid', 'legacysubjectid', 'legacybatchtypeid', 'legacylevelid', 'name', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'],
            [['legacytermid', 'legacysubjectid', 'legacybatchtypeid', 'legacylevelid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
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
            'legacybatchid' => 'Legacybatchid',
            'legacytermid' => 'Legacytermid',
            'legacysubjectid' => 'Legacysubjectid',
            'legacybatchtypeid' => 'Legacybatchtypeid',
            'legacylevelid' => 'Legacylevelid',
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
    public function getLegacyterm()
    {
        return $this->hasOne(LegacyTerm::className(), ['legacytermid' => 'legacytermid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacysubject()
    {
        return $this->hasOne(LegacySubject::className(), ['legacysubjectid' => 'legacysubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacybatchtype()
    {
        return $this->hasOne(LegacyBatchType::className(), ['legacybatchtypeid' => 'legacybatchtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacylevel()
    {
        return $this->hasOne(LegacyLevel::className(), ['legacylevelid' => 'legacylevelid']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyMarksheets()
    {
        return $this->hasMany(LegacyMarksheet::className(), ['legacybatchid' => 'legacybatchid']);
    }
}
