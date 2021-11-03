<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "csec_centre".
 *
 * @property integer $cseccentreid
 * @property string $name
 * @property string $cseccode
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CsecQualification[] $csecQualifications
 */
class CsecCentre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csec_centre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cseccode'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name', 'cseccode'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cseccentreid' => 'Cseccentreid',
            'name' => 'Name',
            'cseccode' => 'Cseccode',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsecQualifications()
    {
        return $this->hasMany(CsecQualification::className(), ['cseccentreid' => 'cseccentreid']);
    }
}
