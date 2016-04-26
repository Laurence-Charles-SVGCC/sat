<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "club_member".
 *
 * @property integer $clubmemberid
 * @property integer $clubid
 * @property integer $personid
 * @property integer $studentregistrationid
 * @property integer $clubroleid
 * @property string $appointmentdate
 * @property string $comments
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 * @property Club $club
 * @property Person $person
 * @property ClubRole $clubrole
 */
class ClubMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clubid', 'personid', 'studentregistrationid', 'clubroleid', 'appointmentdate'], 'required'],
            [['clubid', 'personid', 'studentregistrationid', 'clubroleid', 'isactive', 'isdeleted'], 'integer'],
            [['appointmentdate'], 'safe'],
            [['comments'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clubmemberid' => 'Clubmemberid',
            'clubid' => 'Clubid',
            'personid' => 'Personid',
            'studentregistrationid' => 'Studentregistrationid',
            'clubroleid' => 'Clubroleid',
            'appointmentdate' => 'Appointmentdate',
            'comments' => 'Comments',
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
    public function getClub()
    {
        return $this->hasOne(Club::className(), ['clubid' => 'clubid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubrole()
    {
        return $this->hasOne(ClubRole::className(), ['clubroleid' => 'clubroleid']);
    }
}
