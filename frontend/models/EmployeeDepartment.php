<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee_department".
 *
 * @property string $employeedepartmentid
 * @property string $departmentid
 * @property string $personid
 * @property string $startdate
 * @property string $enddate
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Department $department
 * @property Person $person
 */
class EmployeeDepartment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departmentid', 'personid'], 'required'],
            [['departmentid', 'personid'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
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
        return $this->hasOne(Department::className(), ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }
}
