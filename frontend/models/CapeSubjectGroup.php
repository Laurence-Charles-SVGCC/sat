<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cape_subject_group".
 *
 * @property string $capegroupid
 * @property string $capesubjectid
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property CapeGroup $capegroup
 * @property CapeSubject $capesubject
 */
class CapeSubjectGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_subject_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capegroupid', 'capesubjectid'], 'required'],
            [['capegroupid', 'capesubjectid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capegroupid' => 'Capegroupid',
            'capesubjectid' => 'Capesubjectid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapegroup()
    {
        return $this->hasOne(CapeGroup::className(), ['capegroupid' => 'capegroupid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }
}
