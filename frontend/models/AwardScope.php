<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "award_scope".
 *
 * @property integer $awardscopeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 */
class AwardScope extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'award_scope';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'awardscopeid' => 'Awardscopeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
