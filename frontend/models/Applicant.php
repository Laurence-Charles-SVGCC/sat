<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "applicant".
 *
 * @property string $applicantid
 * @property string $applicantstatustypeid
 * @property string $personid
 * @property string $potentialstudentid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $photopath
 * @property boolean $bursarystatus
 * @property string $sponsorname
 * @property string $clubs
 * @property string $otherinterests
 * @property boolean $isactive
 * @property boolean $isdeleted
 * @property string $maritalstatus
 * @property string $nationality
 * @property string $religion
 * @property string $placeofbirth
 *
 * @property ApplicantStatusType $applicantstatustype
 * @property Person $person
 */
class Applicant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicantstatustypeid', 'personid', 'potentialstudentid'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['bursarystatus', 'isactive', 'isdeleted'], 'boolean'],
            [['clubs', 'otherinterests'], 'string'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'sponsorname', 'nationality', 'religion', 'placeofbirth'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath'], 'string', 'max' => 100],
            [['maritalstatus'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantid' => 'Applicantid',
            'applicantstatustypeid' => 'Applicantstatustypeid',
            'personid' => 'Personid',
            'potentialstudentid' => 'Potentialstudentid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'photopath' => 'Photopath',
            'bursarystatus' => 'Bursarystatus',
            'sponsorname' => 'Sponsorname',
            'clubs' => 'Clubs',
            'otherinterests' => 'Otherinterests',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'maritalstatus' => 'Maritalstatus',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Placeofbirth',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantstatustype()
    {
        return $this->hasOne(ApplicantStatusType::className(), ['applicantstatustypeid' => 'applicantstatustypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['username' => 'personid']);
    }
}
