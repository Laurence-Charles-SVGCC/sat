<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_subject".
 *
 * @property string $legacysubjectid
 * @property string $legacysubjecttypeid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyBatch[] $legacyBatches
 * @property LegacySubjectType $legacysubjecttype
 */
class LegacySubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legacysubjecttypeid', 'name'], 'required'],
            [['legacysubjecttypeid', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacysubjectid' => 'Legacysubjectid',
            'legacysubjecttypeid' => 'Legacysubjecttypeid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyBatches()
    {
        return $this->hasMany(LegacyBatch::className(), ['legacysubjectid' => 'legacysubjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacysubjecttype()
    {
        return $this->hasOne(LegacySubjectType::className(), ['legacysubjecttypeid' => 'legacysubjecttypeid']);
    }
}
