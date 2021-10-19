<?php

namespace common\models;

use yii\base\Model;

class EmployeeProfileForm extends Model
{
    public $username;
    public $title;
    public $firstname;
    public $middlenames;
    public $lastname;
    public $gender;
    public $job_title;
    public $personal_email;
    public $institutional_email;
    public $division;
    public $department;
    public $date_of_birth;
    public $marital_status;
    public $nationality;
    public $place_of_birth;
    public $religion;
    public $nationalid_number;
    public $nis_number;
    public $ird_number;


    public function rules()
    {
        return [
            [
                [
                    'username', 'firstname', 'lastname', 'division',
                    'department', 'institutional_email'
                ],
                'required'
            ],
            [['institutional_email', 'personal_email'], 'email'],
            [['institutional_email', 'personal_email'], 'string', 'max' => 100],
            [['username'], 'string', 'min' => 8],
            [['username'], 'string', 'max' => 8],
            [['job_title', 'division', 'department'], 'integer'],
            [['dateofbirth'], 'safe'],
            [['title'], 'string', 'max' => 4],
            [
                [
                    'firstname', 'middlenames', 'lastname', 'marital_status',
                    'nationality', 'religion', 'place_of_birth',
                    'nationalid_number', 'nis_number', 'ird_number'
                ],
                'string', 'max' => 45
            ],
            [['gender'], 'string', 'max' => 6],


            [
                ['job_title'],
                'exist',
                'skipOnError' => true,
                'targetClass' =>
                EmployeeTitle::class,
                'targetAttribute' => ['job_title' => 'employeetitleid']
            ],
            [
                ['department'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Department::class,
                'targetAttribute' => ['department' => 'departmentid']
            ],
            [
                ['division'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Division::class,
                'targetAttribute' => ['division' => 'divisionid']
            ],
        ];
    }
}
