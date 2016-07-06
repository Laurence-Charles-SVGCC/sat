<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_subject_type".
 *
 * @property string $legacysubjecttypeid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacySubject[] $legacySubjects
 */
class LegacySubjectType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_subject_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacysubjecttypeid' => 'Legacysubjecttypeid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacySubjects()
    {
        return $this->hasMany(LegacySubject::className(), ['legacysubjecttypeid' => 'legacysubjecttypeid']);
    }
}
