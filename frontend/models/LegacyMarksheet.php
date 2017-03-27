<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_marksheet".
 *
 * @property string $legacymarksheetid
 * @property string $legacystudentid
 * @property string $legacybatchid
 * @property string $term
 * @property string $exam
 * @property string $final
 * @property string $createdby
 * @property string $datecreated
 * @property string $lastmodifiedby
 * @property string $datemodified
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyStudent $legacystudent
 * @property LegacyBatch $legacybatch
 * @property Person $createdby
 * @property Person $lastmodifiedby
 */
class LegacyMarksheet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_marksheet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legacystudentid', 'legacybatchid', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'], 
            [['legacystudentid', 'legacybatchid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
            [['term', 'exam', 'final'], 'number'],
            [['datecreated', 'datemodified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacymarksheetid' => 'Legacymarksheetid',
            'legacystudentid' => 'Legacystudentid',
            'legacybatchid' => 'Legacybatchid',
            'term' => 'Term',
            'exam' => 'Exam',
            'final' => 'Final',
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
    public function getLegacystudent()
    {
        return $this->hasOne(LegacyStudent::className(), ['legacystudentid' => 'legacystudentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacybatch()
    {
        return $this->hasOne(LegacyBatch::className(), ['legacybatchid' => 'legacybatchid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lastmodifiedby']);
    }
    
}
