<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "graduation_report".
 *
 * @property string $graduationreportid
 * @property string $personid
 * @property string $studentregistrationid
 * @property string $approvedby
 * @property string $title
 * @property string $firstname
 * @property string $middlenames
 * @property string $lastname
 * @property string $programme
 * @property integer $total_credits
 * @property integer $total_passes
 * @property integer $iseligible
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property StudentStatus $studentregistration
 * @property Person $approvedby0
 */
class GraduationReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'graduation_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'studentregistrationid', 'title', 'firstname', 'lastname', 'programme', 'total_credits', 'total_passes', 'iseligible'], 'required'],
            [['personid', 'studentregistrationid', 'approvedby', 'total_credits', 'total_passes', 'iseligible', 'isactive', 'isdeleted'], 'integer'],
            [['title'], 'string', 'max' => 5],
            [['firstname', 'middlenames', 'lastname', 'programme'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'graduationreportid' => 'Graduationreportid',
            'personid' => 'Personid',
            'studentregistrationid' => 'Studentregistrationid',
            'approvedby' => 'Approvedby',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlenames' => 'Middlenames',
            'lastname' => 'Lastname',
            'programme' => 'Programme',
            'total_credits' => 'Total Credits',
            'total_passes' => 'Total Passes',
            'iseligible' => 'Iseligible',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
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
    public function getStudentregistration()
    {
        return $this->hasOne(StudentStatus::className(), ['studentstatusid' => 'studentregistrationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'approvedby']);
    }
}
