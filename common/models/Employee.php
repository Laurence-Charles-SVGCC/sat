<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $employeeid
 * @property int $personid
 * @property int $employeetitleid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $maritalstatus
 * @property string $nationality
 * @property string $religion
 * @property string $placeofbirth
 * @property string $photopath
 * @property string $nationalidnumber
 * @property string $nationalinsurancenumber
 * @property string $inlandrevenuenumber
 * @property string $signaturepath
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property User $person
 * @property EmployeeTitle $employeetitle
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personid', 'firstname', 'lastname'], 'required'],
            [['personid', 'employeetitleid', 'isactive', 'isdeleted'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['title'], 'string', 'max' => 4],
            [['firstname', 'middlename', 'lastname', 'maritalstatus', 'nationality', 'religion', 'placeofbirth', 'nationalidnumber', 'nationalinsurancenumber', 'inlandrevenuenumber'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath', 'signaturepath'], 'string', 'max' => 100],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['personid' => 'personid']],
            [['employeetitleid'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeTitle::class, 'targetAttribute' => ['employeetitleid' => 'employeetitleid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employeeid' => 'Employeeid',
            'personid' => 'Personid',
            'employeetitleid' => 'Employeetitleid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'maritalstatus' => 'Maritalstatus',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Placeofbirth',
            'photopath' => 'Photopath',
            'nationalidnumber' => 'Nationalidnumber',
            'nationalinsurancenumber' => 'Nationalinsurancenumber',
            'inlandrevenuenumber' => 'Inlandrevenuenumber',
            'signaturepath' => 'Signaturepath',
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
    public function getEmployeetitle()
    {
        return $this->hasOne(EmployeeTitle::class, ['employeetitleid' => 'employeetitleid']);
    }
}
