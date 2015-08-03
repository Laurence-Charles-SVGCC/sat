<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "applicant".
 *
 * @property string $applicantid
 * @property string $applicanttypeid
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
* @property ApplicantStatusType $applicanttype
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
            [['applicanttypeid', 'personid', 'potentialstudentid'], 'integer'],
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
            'applicanttypeid' => 'Applicanttypeid',
            'personid' => 'Personid',
            'potentialstudentid' => 'Potentialstudentid',
            'title' => 'Title',
            'firstname' => 'First Name',
            'middlename' => 'Middle Name',
            'lastname' => 'Last Name',
            'gender' => 'Gender',
            'dateofbirth' => 'Date of Birth',
            'photopath' => 'Photo Path',
            'bursarystatus' => 'Bursary Status',
            'sponsorname' => 'Sponsor',
            'clubs' => 'Clubs',
            'otherinterests' => 'Other Interests',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'maritalstatus' => 'Marital Status',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Place of Birth',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicanttype()
    {
        return $this->hasOne(ApplicantStatusType::className(), ['applicantstatustypeid' => 'applicanttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
