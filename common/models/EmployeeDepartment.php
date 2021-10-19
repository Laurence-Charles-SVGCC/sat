<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee_department".
 *
 * @property int $employeedepartmentid
 * @property int $departmentid
 * @property int $personid
 * @property string $startdate
 * @property string $enddate
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Department $department
 * @property Person $person
 */
class EmployeeDepartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departmentid', 'personid'], 'required'],
            [['departmentid', 'personid', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['departmentid'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentid' => 'departmentid']],
            [['personid'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['personid' => 'personid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employeedepartmentid' => 'Employeedepartmentid',
            'departmentid' => 'Departmentid',
            'personid' => 'Personid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['personid' => 'personid']);
    }
}
