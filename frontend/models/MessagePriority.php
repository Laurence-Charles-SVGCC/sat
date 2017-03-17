<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "message_priority".
 *
 * @property string $messagepriorityid
 * @property string $name
 * @property integer $ordering
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Message[] $messages
 */
class MessagePriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message_priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ordering'], 'required'],
            [['ordering', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'messagepriorityid' => 'Messagepriorityid',
            'name' => 'Name',
            'ordering' => 'Ordering',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['messagepriorityid' => 'messagepriorityid']);
    }
}
