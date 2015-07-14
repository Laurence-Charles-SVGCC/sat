<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property string $employeeid
 * @property string $personid
 * @property string $contactinfoid
 * @property string $maritalstatusid
 * @property string $employeetitleid
 * @property string $religionid
 * @property string $placeofbirthid
 * @property string $nationalityid
 * @property string $title
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $dateofbirth
 * @property string $photopath
 * @property string $nationalidnumber
 * @property string $nationalinsurancenumber
 * @property string $inlandrevenuenumber
 * @property string $signaturepath
 * @property boolean $isactive
 * @property boolean $isdeleted
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
            [['personid', 'contactinfoid', 'firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'min' => 2, 'max' => 255],
            [['personid', 'contactinfoid', 'maritalstatusid', 'employeetitleid', 'religionid', 'placeofbirthid', 'nationalityid'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'middlename', 'lastname', 'nationalidnumber', 'nationalinsurancenumber', 'inlandrevenuenumber'], 'string', 'max' => 45],
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
            'contactinfoid' => 'Contactinfoid',
            'maritalstatusid' => 'Maritalstatusid',
            'employeetitleid' => 'Employeetitleid',
            'religionid' => 'Religionid',
            'placeofbirthid' => 'Placeofbirthid',
            'nationalityid' => 'Nationalityid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'gender' => 'Gender',
            'dateofbirth' => 'Dateofbirth',
            'photopath' => 'Photopath',
            'nationalidnumber' => 'Nationalidnumber',
            'nationalinsurancenumber' => 'Nationalinsurancenumber',
            'inlandrevenuenumber' => 'Inlandrevenuenumber',
            'signaturepath' => 'Signaturepath',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
