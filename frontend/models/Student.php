<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "student".
 *
 * @property string $studentid
 * @property string $personid
 * @property string $applicantname
 * @property string $admissiondate
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property integer $isactive
 * @property integer $isdeleted
 * @property string $email
 *
 * @property Person $person
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'applicantname', 'admissiondate', 'title', 'firstname', 'middlename', 'lastname', 'gender', 'dateofbirth'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['admissiondate', 'dateofbirth'], 'safe'],
            [['applicantname', 'firstname', 'middlename', 'lastname', 'email'], 'string', 'max' => 45],
            [['title'], 'string', 'max' => 3],
            [['gender'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
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
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
