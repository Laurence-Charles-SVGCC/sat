<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applicant_registration".
 *
 * @property int $applicantregistrationid
 * @property int $applicantintentid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $applicantname
 * @property string $created_at
 * @property string $token
 * @property string $updated_at
 * @property int $isactive
 *
 * @property ApplicantIntent $applicantintent
 */
class ApplicantRegistration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicantintentid', 'isactive'], 'integer'],
            [['title', 'firstname', 'lastname', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'lastname', 'email', 'applicantname'], 'string', 'max' => 45],
            [['token'], 'string', 'max' => 255],
            [['applicantintentid'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantIntent::className(), 'targetAttribute' => ['applicantintentid' => 'applicantintentid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicantregistrationid' => 'Applicantregistrationid',
            'applicantintentid' => 'Applicantintentid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'applicantname' => 'Applicantname',
            'created_at' => 'Created At',
            'token' => 'Token',
            'updated_at' => 'Updated At',
            'isactive' => 'Isactive',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantintent()
    {
        return $this->hasOne(ApplicantIntent::className(), ['applicantintentid' => 'applicantintentid']);
    }
}
