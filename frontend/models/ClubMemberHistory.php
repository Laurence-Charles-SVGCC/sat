<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "club_member_history".
 *
 * @property integer $clubmemberhistoryid
 * @property integer $clubid
 * @property integer $studentregistrationid
 * @property integer $personid
 * @property string $startdate
 * @property string $enddate
 * @property integer $oldclubroleid
 * @property integer $newclubroleid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ClubRole $newclubrole
 * @property Club $club
 * @property StudentRegistration $studentregistration
 * @property Person $person
 * @property ClubRole $oldclubrole
 */
class ClubMemberHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club_member_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clubid', 'studentregistrationid', 'personid', 'newclubroleid'], 'required'],
            [['clubid', 'studentregistrationid', 'personid', 'oldclubroleid', 'newclubroleid', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clubmemberhistoryid' => 'Clubmemberhistoryid',
            'clubid' => 'Clubid',
            'studentregistrationid' => 'Studentregistrationid',
            'personid' => 'Personid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'oldclubroleid' => 'Oldclubroleid',
            'newclubroleid' => 'Newclubroleid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewclubrole()
    {
        return $this->hasOne(ClubRole::className(), ['clubroleid' => 'newclubroleid']);
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
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
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
    public function getOldclubrole()
    {
        return $this->hasOne(ClubRole::className(), ['clubroleid' => 'oldclubroleid']);
    }
}
