<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $studentid
 * @property int $personid
 * @property string $applicantname
 * @property string $admissiondate
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property int $isactive
 * @property int $isdeleted
 * @property string $email
 *
 * @property User $person
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid', 'applicantname', 'admissiondate', 'firstname', 'lastname', 'gender', 'dateofbirth'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['admissiondate', 'dateofbirth'], 'safe'],
            [['applicantname', 'firstname', 'middlename', 'lastname', 'email'], 'string', 'max' => 45],
            [['title'], 'string', 'max' => 4],
            [['gender'], 'string', 'max' => 6],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'studentid' => 'Studentid',
            'personid' => 'Personid',
            'applicantname' => 'Applicantname',
            'admissiondate' => 'Admissiondate',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::class, ['personid' => 'personid']);
    }
}
