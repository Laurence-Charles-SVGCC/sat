<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $eventid
 * @property integer $eventtypeid
 * @property integer $studentregistrationid
 * @property integer $recordid
 * @property string $date
 * @property string $summary
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property EventType $eventtype
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eventtypeid', 'studentregistrationid', 'recordid', 'date', 'summary'], 'required'],
            [['eventtypeid', 'studentregistrationid', 'recordid', 'isactive', 'isdeleted'], 'integer'],
            [['date'], 'safe'],
            [['summary'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'eventid' => 'Eventid',
            'eventtypeid' => 'Eventtypeid',
            'studentregistrationid' => 'Studentregistrationid',
            'recordid' => 'Recordid',
            'date' => 'Date',
            'summary' => 'Summary',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventtype()
    {
        return $this->hasOne(EventType::className(), ['eventtypeid' => 'eventtypeid']);
    }
    
    
    /**
     * 
     * @param type $studentregistrationid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 01/05/2016
     * Date Last Modified: 01/05/2016
     */
    public static function getEvents($studentregistrationid)
    {
        $events = Event::find()
                ->where(['studentregistrationid' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        return $events;
    }
}
