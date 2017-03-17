<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property string $messageid
 * @property string $senderid
 * @property string $receipientid
 * @property string $messagepriorityid
 * @property string $date_sent
 * @property string $date_read
 * @property string $sender
 * @property string $topic
 * @property string $content
 * @property integer $isread
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $sender0
 * @property Person $receipient
 * @property MessagePriority $messagepriority
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senderid', 'receipientid', 'messagepriorityid', 'sender', 'topic', 'content'], 'required'],
            [['senderid', 'receipientid', 'messagepriorityid', 'isread', 'isactive', 'isdeleted'], 'integer'],
            [['content'], 'string'],
            [['sender'], 'string', 'max' => 45],
            [['date_sent', 'date_read'], 'safe'],
            [['topic'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'messageid' => 'Messageid',
            'senderid' => 'Senderid',
            'receipientid' => 'Receipientid',
            'messagepriorityid' => 'Messagepriorityid',
            'date_sent' => 'Date Read',
            'date_read' => 'Date Sent',
            'sender' => 'Sender',
            'topic' => 'Topic',
            'content' => 'Content',
            'isread' => 'Isread',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'senderid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipient()
    {
        return $this->hasOne(Person::className(), ['personid' => 'receipientid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessagepriority()
    {
        return $this->hasOne(MessagePriority::className(), ['messagepriorityid' => 'messagepriorityid']);
    }
}
