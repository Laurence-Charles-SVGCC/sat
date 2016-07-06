<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "legacy_faculty".
 *
 * @property string $legacyfacultyid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property LegacyStudent[] $legacyStudents
 */
class LegacyFaculty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legacy_faculty';
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
            'legacyfacultyid' => 'Legacyfacultyid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegacyStudents()
    {
        return $this->hasMany(LegacyStudent::className(), ['legacyfacultyid' => 'legacyfacultyid']);
    }
}
