<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "id_card_status".
 *
 * @property integer $id
 * @property string $name
 * @property integer $creator_id
 * @property integer $modifier_id
 * @property string $created_at
 * @property string $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property Person $creator
 * @property Person $modifier
 */
class IdCardStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'id_card_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'creator_id', 'modifier_id', 'is_active', 'is_deleted'], 'required'],
            [['creator_id', 'modifier_id', 'is_active', 'is_deleted'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'creator_id' => 'Creator ID',
            'modifier_id' => 'Modifier ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Person::className(), ['personid' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasOne(Person::className(), ['personid' => 'modifier_id']);
    }
}
