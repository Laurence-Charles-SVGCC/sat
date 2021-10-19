<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee_title".
 *
 * @property int $employeetitleid
 * @property int $employeecategoryid
 * @property string $name
 * @property int $issenior
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Employee[] $employees
 * @property EmployeeCategory $employeecategory
 */
class EmployeeTitle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_title';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employeecategoryid', 'name'], 'required'],
            [['employeecategoryid', 'issenior', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['employeecategoryid'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeCategory::class, 'targetAttribute' => ['employeecategoryid' => 'employeecategoryid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employeetitleid' => 'Employeetitleid',
            'employeecategoryid' => 'Employeecategoryid',
            'name' => 'Name',
            'issenior' => 'Issenior',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::class, ['employeetitleid' => 'employeetitleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeecategory()
    {
        return $this->hasOne(EmployeeCategory::class, ['employeecategoryid' => 'employeecategoryid']);
    }
}
