<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_batch".
 *
 * @property string $legacybatchid
 * @property string $legacytermid
 * @property string $legacysubjectid
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
 * @property LegacyLevel $legacylevel
 * @property Person $createdby
 * @property Person $lastmodifiedby
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
            [['legacytermid', 'legacysubjectid', 'legacylevelid', 'name', 'createdby', 'datecreated', 'lastmodifiedby', 'datemodified'], 'required'],
            [['legacytermid', 'legacysubjectid', 'legacylevelid', 'createdby', 'lastmodifiedby', 'isactive', 'isdeleted'], 'integer'],
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
    public function getLegacylevel()
    {
        return $this->hasOne(LegacyLevel::className(), ['legacylevelid' => 'legacylevelid']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyMarksheets()
    {
        return $this->hasMany(LegacyMarksheet::className(), ['legacybatchid' => 'legacybatchid']);
    }
    
    
    /**
     * Returns LEgacyBatch records with
     * 
     * @param type $termid
     * @param type $levelid
     * @param type $subjectid
     * 
     * Author: Laurence Charles
     * Date Created: 23/03/2017
     * Date Last Modified: 23/03/2017
     */
    public static function getBatchesWithGrades($termid = null , $levelid = null, $subjectid = null)
    {
        $cond_arr['legacy_batch.isactive'] = 1;
        $cond_arr['legacy_batch.isdeleted'] = 0;
        $cond_arr['legacy_marksheet.isactive'] = 1;
        $cond_arr['legacy_marksheet.isdeleted'] = 0;
        
        if ($termid != null)
            $cond_arr['legacy_batch.legacytermid'] = $termid;
        
        if ($levelid != null)
            $cond_arr['legacy_batch.legacylevelid'] = $levelid;
        
        if ($subjectid != null)
            $cond_arr['legacy_batch.subjectid'] = $subjectid;
        
         $batches = LegacyBatch::find()
                    ->innerJoin('legacy_marksheet', '`legacy_batch`.`legacybatchid` = `legacy_marksheet`.`legacybatchid`')
                    ->where($cond_arr)
                    ->all();
         return $batches;
    }
}
