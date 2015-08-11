<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "employee".
 *
 * @property string $employeeid
 * @property string $personid
 * @property string $employeetitleid
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
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property EmployeeTitle $employeetitle
 * @property Person $person
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'firstname', 'lastname'], 'required'],
            [['personid', 'employeetitleid'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'maritalstatus', 'nationality', 'religion', 'placeofbirth', 'nationalidnumber', 'nationalinsurancenumber', 'inlandrevenuenumber'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 6],
            [['photopath', 'signaturepath'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employeeid' => 'Employeeid',
            'personid' => 'Personid',
            'employeetitleid' => 'Job Title',
            'title' => 'Title',
            'firstname' => 'First Name',
            'middlename' => 'Middle Name(s)',
            'lastname' => 'Last Name',
            'gender' => 'Gender',
            'dateofbirth' => 'Date of Birth',
            'maritalstatus' => 'Marital Status',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'placeofbirth' => 'Place of Birth',
            'photopath' => 'Photo Path',
            'nationalidnumber' => 'National ID Number',
            'nationalinsurancenumber' => 'National Insurance Number',
            'inlandrevenuenumber' => 'Inland Revenue Number',
            'signaturepath' => 'Signature Path',
            'isactive' => 'Active',
            'isdeleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeetitle()
    {
        return $this->hasOne(EmployeeTitle::className(), ['employeetitleid' => 'employeetitleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
}
