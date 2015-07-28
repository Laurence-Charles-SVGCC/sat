<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cape_group".
 *
 * @property string $capegroupid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property CapeSubjectGroup[] $capeSubjectGroups
 * @property CapeSubject[] $capesubjects
 */
class CapeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capegroupid' => 'Capegroupid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapeSubjectGroups()
    {
        return $this->hasMany(CapeSubjectGroup::className(), ['capegroupid' => 'capegroupid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubjects()
    {
        return $this->hasMany(CapeSubject::className(), ['capesubjectid' => 'capesubjectid'])->viaTable('cape_subject_group', ['capegroupid' => 'capegroupid']);
    }
}
