<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "person_award".
 *
 * @property integer $personawardid
 * @property integer $personid
 * @property integer $awardid
 * @property integer $studentregistrationid
 * @property string $dateawarded
 * @property string $comments
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property Award $award
 * @property StudentRegistration $studentregistration
 */
class PersonAward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'awardid', 'studentregistrationid', 'dateawarded'], 'required'],
            [['personid', 'awardid', 'studentregistrationid', 'isactive', 'isdeleted'], 'integer'],
            [['dateawarded'], 'safe'],
            [['comments'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personawardid' => 'Personawardid',
            'personid' => 'Personid',
            'awardid' => 'Awardid',
            'studentregistrationid' => 'Studentregistrationid',
            'dateawarded' => 'Dateawarded',
            'comments' => 'Comments',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::class, ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAward()
    {
        return $this->hasOne(Award::class, ['awardid' => 'awardid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
