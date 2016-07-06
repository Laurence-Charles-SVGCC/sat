<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_level".
 *
 * @property string $legacylevelid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyBatch[] $legacyBatches
 */
class LegacyLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_level';
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
            'legacylevelid' => 'Legacylevelid',
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
        return $this->hasMany(LegacyBatch::className(), ['legacylevelid' => 'legacylevelid']);
    }
}
