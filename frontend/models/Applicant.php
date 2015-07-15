<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "applicant".
 *
 * @property string $applicantid
 * @property string $contactinfoid
 * @property string $applicantstatustypeid
 * @property string $personid
 * @property string $nationalityid
 * @property string $placeofbirthid
 * @property string $religionid
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
            [['contactinfoid', 'personid', 'nationalityid', 'placeofbirthid', 'religionid', 'potentialstudentid', 'title', 'firstname', 'middlename', 'lastname', 'gender', 'dateofbirth', 'photopath', 'sponsorname', 'clubs'], 'required'],
            [['contactinfoid', 'applicantstatustypeid', 'personid', 'nationalityid', 'placeofbirthid', 'religionid', 'potentialstudentid'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['bursarystatus', 'isactive', 'isdeleted'], 'boolean'],
            [['clubs', 'otherinterests'], 'string'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'sponsorname'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicantid' => 'Applicantid',
            'contactinfoid' => 'Contactinfoid',
            'applicantstatustypeid' => 'Applicantstatustypeid',
            'personid' => 'Personid',
            'nationalityid' => 'Nationalityid',
            'placeofbirthid' => 'Placeofbirthid',
            'religionid' => 'Religionid',
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
        ];
    }
}
