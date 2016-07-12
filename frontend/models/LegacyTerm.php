<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_term".
 *
 * @property string $legacytermid
 * @property string $legacyyearid
 * @property string $name
 * @property integer $ordering
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyBatch[] $legacyBatches
 */
class LegacyTerm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_term';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legacyyearid', 'name', 'ordering'], 'required'],
            [['legacyyearid', 'ordering', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legacytermid' => 'Legacytermid',
            'legacyyearid' => 'Legacyyearid',
            'name' => 'Name',
            'ordering' => 'Ordering',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyBatches()
    {
        return $this->hasMany(LegacyBatch::className(), ['legacytermid' => 'legacytermid']);
    }
}
